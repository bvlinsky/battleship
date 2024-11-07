run:
	docker run -it -v ${PWD}:/battleship -w /battleship composer run game

bash:
	docker run -it -v ${PWD}:/battleship -w /battleship composer bash

fix:
	docker run -it --rm -v ${PWD}:/code ghcr.io/php-cs-fixer/php-cs-fixer:3-php8.3 fix
