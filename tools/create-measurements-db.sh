#!/bin/bash

if [ "$MYSQL_MEASUREMENTS_ENABLED" -ne "0" ] && [ -n "$MYSQL_MEASUREMENTS_ENABLED" ]; then
	DB=${MYSQL_MEASUREMENTS_DATABASE:-measurements}

	echo "MySQL creating '$DB' db.."

	mysql --protocol=socket -uroot -p"$MYSQL_ROOT_PASSWORD" <<-EOSQL
		CREATE DATABASE IF NOT EXISTS \`$DB\`;
		GRANT ALL ON \`$DB\`.* TO '$MYSQL_USER'@'%';
		FLUSH PRIVILEGES;
	EOSQL

	if [ "$?" -eq 0 ]; then
		echo 'Measurements db created!'
	else
		echo 'Error creating measurements db.'
		exit 1
	fi
else
	echo 'MySQL measurements not enabled. Skipping db creation.'
fi
