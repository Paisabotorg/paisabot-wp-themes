.DEFAULT_GOAL := help
.PHONY: help deploy-qa deploy-all

help:
	@echo "  make deploy-qa   Deploy aivartha to qa.paisabot.com"
	@echo "  make deploy-all  Deploy all sites"

deploy-qa:
	FTP_PASSWORD=$$FTP_PASSWORD python3 deploy.py qa

deploy-all:
	FTP_PASSWORD=$$FTP_PASSWORD python3 deploy.py paisabot hi ml tel ta mr gu kn bn or
