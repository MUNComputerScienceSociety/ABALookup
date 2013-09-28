all:
	@echo "Doing nothing."

install:
	@echo "Installing BOWER and Grunt."
	@npm install -g bower
	@npm install -g grunt-cli

dependencies:
	@echo "Installing dependencies."
	@composer install
	@npm install
	@bower install

database:
	@scripts/database

test:
	@scripts/test
