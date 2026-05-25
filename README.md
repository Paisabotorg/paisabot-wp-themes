# paisabot-theme

WordPress themes for the Paisabot / AI Vartha network.

| Theme | Sites |
|-------|-------|
| `aivartha` | paisabot.com, qa.paisabot.com |
| `arthanama` | hi.paisabot.com, ml.paisabot.com, tel.paisabot.com |

## Branch → Deploy mapping

| Branch | Deploys to |
|--------|-----------|
| `release/qa` | qa.paisabot.com only |
| `release/production` | paisabot.com + hi + ml + tel |

## Workflow

```
feature/my-change
    ↓  PR
release/qa          → GitHub Actions → qa.paisabot.com
    ↓  PR (after QA sign-off)
release/production  → GitHub Actions → all 4 production sites
```

## Local deploy

```bash
export FTP_PASSWORD=your_ftp_password

# Deploy to QA only
python deploy.py qa

# Deploy to a specific site
python deploy.py paisabot
python deploy.py hi
python deploy.py ml
python deploy.py tel

# Deploy everywhere
python deploy.py
```

## GitHub Secrets required

| Secret | Description |
|--------|-------------|
| `FTP_PASSWORD` | Hostinger FTP password (shared across all sites) |

Set via: `gh secret set FTP_PASSWORD --repo OWNER/REPO`

## FTP details

- Host: `217.21.85.66:21`
- One password, per-site usernames (see `deploy.py`)
