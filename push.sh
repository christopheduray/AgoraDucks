rsync -av \
	--exclude .env \
	--exclude .git \
	--exclude composer.lock \
	--exclude vendor \
	--exclude storage \
		. \
		www-data@mx.cdy.be:/var/www/clients/ducks/
