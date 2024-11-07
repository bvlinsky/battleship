# Battleship PHP

A simple game of Battleship, written in PHP. The purpose of this repository is to serve as an entry point into coding exercises and it was especially created for scrum.orgs Applying Professional Scrum for Software Development course (www.scrum.org/apssd). The code in this repository is unfinished by design.
Created by Sergey https://github.com/2heoh 

# Getting started

This project requires a php8.3 or higher. To prepare to work with it, pick one of these
options:

## Run locally

Install Docker: https://docs.docker.com/engine/install/

Run battleship in Docker:

```bash
docker build -t battleship .
docker run -it -v ${PWD}:/battleship -w /battleship battleship composer run game
```

## Execute tests with composer

```bash
docker build -t battleship .
docker run -it -v ${PWD}:/battleship -w /battleship battleship bash
composer update
composer run test
```
