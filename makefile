migrate:
	php spark migrate

seed:
	php spark db:seed UserSeeder
	php spark db:seed ModelSeeder
	php spark db:seed CapacitySeeder