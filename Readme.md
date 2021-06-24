# Wordpress hexagonal architecture example

A plugin example created in an hexagonal way. That way of work facilitates future improvements like migrate the code to 
a framework (symfony, laravel...) and next iterations once is migrated like Domain Driven Design (DDD) or CQRS. It can be
used as a wordpress plugin boilerplate.

The purpose of this project is to demonstrate the hexagonal architecture is able to be implemented in most of the places and
facilitates testing, debugging and modification in a more visible way, like a wordpress plugin can provide, without overenginering inside it.

You can see the plugin on: [WordPress Plugins directory](https://wordpress.org/plugins/hexagonal-reviews/)

## It lacks of:

* A Dependency Injector (dependencies are applied manually by a class implementing the PHP FIG PSR-11 Container interface, nowadays most frameworks has it's own dependency autowire) 
* An Event dispatcher (there's a class simulating it in a synced way implementing PHP FIG PSR-14)
* Database Migration manager to manage plugin database updates (Added a very simple migration tool)

## But has features like:

* Docker ready for launching unit and acceptance tests on a wordpress container
* Codeception for automated testing
* Standalone selenium chrome with vnc capabilities to see what the BDD acceptance tests are doing

## How to develop locally

Run: ```make vendors``` and then

Install the project folder inside a wordpress like a normal plugin

### Run Automated Tests

Run: ```make up``` to start docker testing environment and then:

```make unit``` to run unit tests

```make acceptance``` to run acceptance tests

You can see what happens on the browser accessing to the vnc on ```YOUR_LOCAL_DOCKER_IP_ENTRYPOINT:5900``` with password ```secret```


### Deployment

In order to prepare the plugin to publish, we remove the testing folder and dependencies to reduce the size of the plugin.

Run: ```make plugin``` to generate a build folder with the reduced build inside.

### (Experimental) Build with PHP 7.0, 7.4 or 8.0 compatibility

Run: ```make plugin80``` to generate and use rector to build the code php 8.0 compatible. you can also downgrade to
PHP 7.0 using ```make plugin70``` or upgrade to PHP 7.4 using ```make plugin74```

### Coding Standards

The project is using the Wordpress Coding Standards, but ignoring the file naming exceptions and cache warnings.

Run: ```make cs``` to run the CodeSniffer and check your files 

### About the plugin:

The plugin permits you to append reviews on the posts using a shortcode, and manage it's publication on the admin area


