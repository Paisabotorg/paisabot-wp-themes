from pathlib import Path
#!/usr/bin/env python3
"""
deploy-themes.py — Deploy the paisabot theme to all Paisabot WP sites via FTP.

Usage:
    python3 deploy-themes.py                  # deploy all sites
    python3 deploy-themes.py qa paisabot      # deploy specific sites only

Sites: qa, paisabot, hi, ml, tel
"""

import ftplib
import os
import sys
import getpass
import time

# ── Config ──────────────────────────────────────────────────────────────────

FTP_HOST = "217.21.85.66"
FTP_PORT = 21

LOCAL_PAISABOT  = str(Path(__file__).parent / "themes" / "paisabot")
LOCAL_MU_PLUGIN = str(Path(__file__).parent / "mu-plugins" / "paisabot-activate-theme.php")

SITES = {
    "qa": {
        "label":      "qa.paisabot.com",
        "user":       "u928714162.qa.paisabot.com",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "paisabot": {
        "label":      "paisabot.com",
        "user":       "u928714162",
        # Main account lands in its home dir where `cwd public_html` fails
        # silently; the real apex docroot (per auth RUNBOOK) is:
        "base":       "domains/paisabot.com/public_html",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "hi": {
        "label":      "hi.paisabot.com",
        "user":       "u928714162.hi.paisabot.com",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "ml": {
        "label":      "ml.paisabot.com",
        "user":       "u928714162.ml.paisabot.com",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "tel": {
        "label":      "tel.paisabot.com",
        "user":       "u928714162.tel.paisabot.com",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    # ta/mr/gu/kn/bn/or are subdomains of paisabot.com — deploy via main FTP
    # account with explicit base path (subdomain FTP users don't exist for these).
    "ta": {
        "label":      "ta.paisabot.com",
        "user":       "u928714162",
        "base":       "domains/paisabot.com/public_html/ta",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "mr": {
        "label":      "mr.paisabot.com",
        "user":       "u928714162",
        "base":       "domains/paisabot.com/public_html/mr",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "gu": {
        "label":      "gu.paisabot.com",
        "user":       "u928714162",
        "base":       "domains/paisabot.com/public_html/gu",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "kn": {
        "label":      "kn.paisabot.com",
        "user":       "u928714162",
        "base":       "domains/paisabot.com/public_html/kn",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "bn": {
        "label":      "bn.paisabot.com",
        "user":       "u928714162",
        "base":       "domains/paisabot.com/public_html/bn",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
    "or": {
        "label":      "or.paisabot.com",
        "user":       "u928714162",
        "base":       "domains/paisabot.com/public_html/or",
        "theme_dir":  LOCAL_PAISABOT,
        "remote_dir": "wp-content/themes/paisabot",
    },
}

EXCLUDE_DIRS  = {".git", "__pycache__", "node_modules", ".DS_Store"}
EXCLUDE_FILES = {".DS_Store", ".gitignore", ".gitkeep"}

# ── Helpers ──────────────────────────────────────────────────────────────────

def ensure_remote_dir(ftp, path):
    """Create remote directory tree if it doesn't exist."""
    parts = path.strip("/").split("/")
    current = ""
    for part in parts:
        current = current + "/" + part if current else part
        try:
            ftp.mkd(current)
        except ftplib.error_perm as e:
            if "550" not in str(e):  # 550 = already exists, ignore
                raise

def upload_dir(ftp, local_dir, remote_dir):
    """Recursively upload local_dir to remote_dir."""
    total = 0
    failed = []

    for root, dirs, files in os.walk(local_dir):
        # Prune excluded dirs in-place so os.walk skips them
        dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]

        rel_root = os.path.relpath(root, local_dir)
        if rel_root == ".":
            remote_root = remote_dir
        else:
            remote_root = remote_dir + "/" + rel_root.replace(os.sep, "/")

        ensure_remote_dir(ftp, remote_root)

        for filename in files:
            if filename in EXCLUDE_FILES:
                continue

            local_path  = os.path.join(root, filename)
            remote_path = remote_root + "/" + filename

            try:
                with open(local_path, "rb") as f:
                    ftp.storbinary(f"STOR {remote_path}", f)
                total += 1
                print(f"  ✓ {remote_path}")
            except Exception as e:
                failed.append((remote_path, str(e)))
                print(f"  ✗ {remote_path} — {e}")

    return total, failed

def deploy_site(key, cfg, password):
    label      = cfg["label"]
    user       = cfg["user"]
    theme_dir  = cfg["theme_dir"]
    remote_dir = cfg["remote_dir"]

    print(f"\n{'─'*60}")
    print(f"  Deploying → {label}")
    print(f"  User: {user}  |  Theme: {os.path.basename(theme_dir)}")
    print(f"  Remote: public_html/{remote_dir}")
    print(f"{'─'*60}")

    # Retry loop: Hostinger FTP sometimes takes >30s to accept connections from
    # GitHub Actions runners. Two attempts at 60s timeout cover the common case.
    ftp = None
    for attempt in range(1, 3):
        try:
            ftp = ftplib.FTP()
            ftp.connect(FTP_HOST, FTP_PORT, timeout=60)
            ftp.login(user, password)
            ftp.set_pasv(True)
            break
        except Exception as e:
            print(f"  ⚠ Connect attempt {attempt} failed: {e}")
            ftp = None
    if ftp is None:
        raise RuntimeError(f"Could not connect to FTP after 2 attempts")
    try:

        # Navigate to docroot. Sites using the main FTP account (u928714162)
        # have an explicit base path; subdomain FTP users land directly in docroot.
        base = cfg.get("base", "")
        if base:
            ensure_remote_dir(ftp, base)
            ftp.cwd(base)
        else:
            try:
                ftp.cwd("public_html")
            except ftplib.error_perm:
                print("  Note: already at public_html root")

        # Upload the theme FIRST so the paisabot/ folder exists before the
        # mu-plugin forces 'paisabot' as the active theme. Doing it the other
        # way round risks a brief white-screen if the folder isn't there yet.
        start = time.time()
        total, failed = upload_dir(ftp, theme_dir, remote_dir)
        elapsed = time.time() - start

        # Now ensure mu-plugins dir exists and upload theme-activation plugin
        ensure_remote_dir(ftp, "wp-content/mu-plugins")
        try:
            with open(LOCAL_MU_PLUGIN, "rb") as f:
                ftp.storbinary("STOR wp-content/mu-plugins/paisabot-activate-theme.php", f)
            print("  ✓ wp-content/mu-plugins/paisabot-activate-theme.php")
        except Exception as e:
            print(f"  ⚠ mu-plugin upload failed: {e}")

        ftp.quit()

        if failed:
            print(f"\n  ⚠ {len(failed)} file(s) failed:")
            for path, err in failed:
                print(f"    ✗ {path}: {err}")

        status = "✅" if not failed else "⚠️ "
        print(f"\n  {status} {label}: {total} files in {elapsed:.1f}s  ({len(failed)} failed)")
        return len(failed) == 0

    except ftplib.error_perm as e:
        print(f"\n  ❌ Auth failed for {label}: {e}")
        return False
    except Exception as e:
        print(f"\n  ❌ Error for {label}: {e}")
        return False

# ── Main ─────────────────────────────────────────────────────────────────────

def main():
    # Determine which sites to deploy
    requested = sys.argv[1:]
    if requested:
        unknown = [s for s in requested if s not in SITES]
        if unknown:
            print(f"Unknown site(s): {', '.join(unknown)}")
            print(f"Available: {', '.join(SITES.keys())}")
            sys.exit(1)
        targets = {k: SITES[k] for k in requested}
    else:
        targets = SITES

    print("╔══════════════════════════════════════════════════════════╗")
    print("║       AI Vartha — Theme FTP Deploy                      ║")
    print("╚══════════════════════════════════════════════════════════╝")
    print(f"\nSites to deploy: {', '.join(t['label'] for t in targets.values())}")
    print(f"FTP host: {FTP_HOST}:{FTP_PORT}\n")

    password = os.environ.get("FTP_PASSWORD") or getpass.getpass("FTP password: ")

    results = {}
    for key, cfg in targets.items():
        results[key] = deploy_site(key, cfg, password)

    # Summary
    print(f"\n{'═'*60}")
    print("  DEPLOY SUMMARY")
    print(f"{'═'*60}")
    all_ok = True
    for key, ok in results.items():
        site = SITES[key]["label"]
        print(f"  {'✅' if ok else '❌'}  {site}")
        if not ok:
            all_ok = False

    print()
    if all_ok:
        print("  All sites deployed successfully.")
    else:
        print("  Some sites failed — check errors above.")
        sys.exit(1)

if __name__ == "__main__":
    main()
