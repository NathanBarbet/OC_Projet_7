# OC_Projet_7

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/744ade0ff3f14a33b47e9d33a8aee130)](https://www.codacy.com/manual/NathanBarbet/OC_Projet_7?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=NathanBarbet/OC_Projet_7&amp;utm_campaign=Badge_Grade)

Php version : 7.3.12

Documentation : http://nathanbarbet.fr/P7/public/bilemo/doc
Exemple : http://nathanbarbet.fr/P7/public

for installation:

1/ Import the oc_projet_7.sql file into your database.

2/ Copy all of files and folders to the root of your site.

3/ Run "composer install" for install all of dependencies.

4/ Edit the .env (DATABASE_URL) file with your own database login.

5/ Generate new SSH key for JWT Bundle :

Generate the SSH keys:

$ mkdir -p config/jwt

$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096

$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

6/ Past your passphrase in .env file at the line "JWT_PASSPHRASE=".
