Contribution Guide
==================

## Requirements

- When you're adding or removing site pages, make sure that they are correctly routed in [Nginx configuration file](_helm/partnership/conf/nginx/vhost.conf).
- When you're changing project files layout, make sure that project distribution is created as expected [on `dist` stage in `Dockerfile`](Dockerfile).
- When you're adding or removing [Composer dependencies](composer.json), make sure that they are properly cleaned [on `dist` stage in `Dockerfile`](Dockerfile).
- If you don't use docker-wrapped commands, make sure that tools you use have the same version as in docker-wrapped commands. It's [latest version](.gitlab-ci.yml), mainly.




## Operations

Take a look at [`Makefile`] for commands usage details.




### Local development

Use `docker-compose` from [`Makefile`] to boot up [Docker Compose environment](docker-compose.yml) for local development:
```bash
make up
make down
```




### Dependencies

To resolve all project dependencies use docker-wrapped command from [`Makefile`]:
```bash
make deps

# or concrete type
make deps.composer
```

To upgrade project dependencies use docker-wrapped commands from [`Makefile`]:
```bash
make deps.composer cmd=update
```




### Building

To build/rebuild project Docker image use docker-wrapped command from [`Makefile`]:
```bash
make build.docker
```





[`Makefile`]: Makefile
[`composer.json`]: composer.json
