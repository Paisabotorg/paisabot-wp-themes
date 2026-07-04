#!/usr/bin/env python3
"""
cleanup_themes.py — Remove every theme except 'paisabot' from all Paisabot WP
sites via FTP.

SAFETY MODEL
────────────
- Allowlist: only KEEP_THEME ('paisabot') survives. Everything else under
  wp-content/themes/ on each site is deleted.
- DRY RUN BY DEFAULT. Nothing is deleted unless you pass --execute.
- The active theme is forced to 'paisabot' by the mu-plugin
  (paisabot-activate-theme.php), so removing WordPress default themes is safe
  as long as that mu-plugin and the paisabot/ folder are present. Deploy the
  theme (deploy.py) BEFORE running this with --execute.
- 'paisabot' is hard-protected: it is skipped even if passed explicitly.

Usage:
    python3 cleanup_themes.py                 # DRY RUN, all sites — shows what would be deleted
    python3 cleanup_themes.py qa              # DRY RUN, qa only
    python3 cleanup_themes.py --execute       # ACTUALLY delete, all sites
    python3 cleanup_themes.py qa --execute    # ACTUALLY delete, qa only

Sites: qa, paisabot, hi, ml, tel  (same FTP layout as deploy.py)
"""

import ftplib
import os
import sys
import getpass
import time

# ── Config ──────────────────────────────────────────────────────────────────

FTP_HOST = "217.21.85.66"
FTP_PORT = 21

KEEP_THEME   = "paisabot"            # the ONLY theme that survives
THEMES_REMOTE = "wp-content/themes"  # relative to public_html

# Same users as deploy.py
SITES = {
    "qa":       {"label": "qa.paisabot.com",  "user": "u928714162.qa.paisabot.com"},
    "paisabot": {"label": "paisabot.com",     "user": "u928714162"},
    "hi":       {"label": "hi.paisabot.com",  "user": "u928714162.hi.paisabot.com"},
    "ml":       {"label": "ml.paisabot.com",  "user": "u928714162.ml.paisabot.com"},
    "tel":      {"label": "tel.paisabot.com", "user": "u928714162.tel.paisabot.com"},
}

# ── FTP helpers ──────────────────────────────────────────────────────────────

def list_dir(ftp, path):
    """Return (subdirs, files) under `path`. Uses MLSD when available."""
    subdirs, files = [], []
    try:
        for name, facts in ftp.mlsd(path):
            if name in (".", ".."):
                continue
            (subdirs if facts.get("type") == "dir" else files).append(name)
        return subdirs, files
    except ftplib.error_perm:
        pass
    # Fallback: NLST + probe each entry by trying to cwd into it
    names = []
    try:
        names = [n.split("/")[-1] for n in ftp.nlst(path)]
    except ftplib.error_perm:
        return [], []
    cwd = ftp.pwd()
    for name in names:
        if name in (".", ".."):
            continue
        try:
            ftp.cwd(f"{path}/{name}")
            subdirs.append(name)
            ftp.cwd(cwd)
        except ftplib.error_perm:
            files.append(name)
    return subdirs, files


def delete_tree(ftp, path, dry_run, indent="    "):
    """Recursively delete a remote directory and everything under it."""
    subdirs, files = list_dir(ftp, path)
    for f in files:
        target = f"{path}/{f}"
        if dry_run:
            print(f"{indent}would DELETE file  {target}")
        else:
            try:
                ftp.delete(target)
                print(f"{indent}✓ deleted file  {target}")
            except Exception as e:
                print(f"{indent}✗ file  {target} — {e}")
    for d in subdirs:
        delete_tree(ftp, f"{path}/{d}", dry_run, indent + "  ")
    if dry_run:
        print(f"{indent}would RMDIR        {path}")
    else:
        try:
            ftp.rmd(path)
            print(f"{indent}✓ rmdir         {path}")
        except Exception as e:
            print(f"{indent}✗ rmdir {path} — {e}")


def cleanup_site(key, cfg, password, dry_run):
    label = cfg["label"]
    user  = cfg["user"]
    print(f"\n{'─'*60}")
    print(f"  {'DRY RUN' if dry_run else 'EXECUTE'} → {label}  (user: {user})")
    print(f"{'─'*60}")

    try:
        ftp = ftplib.FTP()
        ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
        ftp.login(user, password)
        ftp.set_pasv(True)
        try:
            ftp.cwd("public_html")
        except ftplib.error_perm:
            print("  Note: already at public_html root")

        subdirs, _ = list_dir(ftp, THEMES_REMOTE)
        if not subdirs:
            print(f"  (no theme folders found under {THEMES_REMOTE})")
            ftp.quit()
            return True

        to_delete = [d for d in subdirs if d != KEEP_THEME]
        print(f"  Found themes: {', '.join(sorted(subdirs))}")
        print(f"  Keeping:      {KEEP_THEME}")
        if KEEP_THEME not in subdirs:
            print(f"  ⚠ WARNING: '{KEEP_THEME}' is NOT present on this site. "
                  f"Deploy the theme first — ABORTING this site to avoid a dead site.")
            ftp.quit()
            return False
        if not to_delete:
            print("  Nothing to remove — already clean.")
            ftp.quit()
            return True
        print(f"  Removing:     {', '.join(sorted(to_delete))}")

        for d in to_delete:
            delete_tree(ftp, f"{THEMES_REMOTE}/{d}", dry_run)

        ftp.quit()
        return True
    except ftplib.error_perm as e:
        print(f"  ❌ Auth/permission error for {label}: {e}")
        return False
    except Exception as e:
        print(f"  ❌ Error for {label}: {e}")
        return False


# ── Main ─────────────────────────────────────────────────────────────────────

def main():
    args = sys.argv[1:]
    execute = "--execute" in args
    requested = [a for a in args if not a.startswith("--")]

    if requested:
        unknown = [s for s in requested if s not in SITES]
        if unknown:
            print(f"Unknown site(s): {', '.join(unknown)}")
            print(f"Available: {', '.join(SITES.keys())}")
            sys.exit(1)
        targets = {k: SITES[k] for k in requested}
    else:
        targets = SITES

    dry_run = not execute
    print("╔══════════════════════════════════════════════════════════╗")
    print("║   PaisaBot — Theme Cleanup (keep 'paisabot' only)        ║")
    print("╚══════════════════════════════════════════════════════════╝")
    print(f"  Mode: {'DRY RUN (nothing deleted)' if dry_run else '*** EXECUTE — WILL DELETE ***'}")
    print(f"  Sites: {', '.join(t['label'] for t in targets.values())}")

    if execute:
        print("\n  You are about to permanently delete theme folders on LIVE sites.")
        confirm = input("  Type 'DELETE' to proceed: ").strip()
        if confirm != "DELETE":
            print("  Aborted.")
            sys.exit(0)

    password = os.environ.get("FTP_PASSWORD") or getpass.getpass("FTP password: ")

    results = {}
    for key, cfg in targets.items():
        results[key] = cleanup_site(key, cfg, password, dry_run)
        time.sleep(0.5)

    print(f"\n{'═'*60}")
    print("  CLEANUP SUMMARY" + ("  (dry run)" if dry_run else ""))
    print(f"{'═'*60}")
    for key, ok in results.items():
        print(f"  {'✅' if ok else '❌'}  {SITES[key]['label']}")
    if dry_run:
        print("\n  Dry run only. Re-run with --execute to actually delete.")


if __name__ == "__main__":
    main()
