.PHONY: all
all: tests

.PHONY: tests
tests:
	./vendor/bin/phpunit tests
