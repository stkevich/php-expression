To use xdebug
===

  docker run --rm \\
  -v $(pwd):/code \\
  --env "XDEBUG_CONFIG=remote_host=docker.for.mac.localhost remote_port=9000" \
  --env "PHP_IDE_CONFIG=serverName=app" \
  myapp-xdebug php /code/run.php