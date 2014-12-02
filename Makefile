test-unit:
	./vendor/bin/tester tests/


reload-database:
	vagrant ssh -c "mysql -u root -ppass < /vagrant/database/create-schema.sql"
