test-unit:
	./vendor/bin/tester tests/


reload-database:
	vagrant ssh -c "mysql -u root -ppass < /vagrant/database/create-schema.sql"


# install development dependencies
install-dependencies:
	composer create-project apigen/apigen dev-dependencies/apigen v4.0.0


# generate documentation
generate-documentation:
	rm -rf doc/
	dev-dependencies/apigen/bin/apigen generate \
		--source src/ --destination doc/ \
		--tree --todo --deprecated
