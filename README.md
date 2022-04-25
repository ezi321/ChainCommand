Console Command Chaining
========================

Clone the repository to test

```bash
$ git clone git@github.com:ezi321/ChainCommand.git
```

Then run:

```bash
$ cd ChainCommand/project && composer install
```

To run tests:

```bash
$ cd ChainCommand/bundle/CommandChainBundle && composer install --dev
```

then

```bash
$ php ./vendor/bin/phpunit
```

The test task contain several folders:
========================
- bundle - contains CommandChainBundle, and example FooBundle, BarBundle that provide commands to chain
- project - contain symfony lts skeleton