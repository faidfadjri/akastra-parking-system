migrate:
	php spark migrate

seed:
	php spark db:seed UserSeeder
	php spark db:seed ModelSeeder
	php spark db:seed CapacitySeeder
	php spark db:seed ParkingGrupSeeder

serve:
	php spark serve