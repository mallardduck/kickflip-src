<h1 align="center">Kickflip Site Builder</h1>
<p align="center">
    <strong>The monorepo for Kickflip site generator and the docs.</strong>
</p>

## About

Kickflip is a Laravel Zero based CLI tool that generates a static HTML site from markdown and blade template files.
This is the monorepo that houses the [Kickflip-cli](https://github.com/mallardduck/kickflip-cli) Site builder and the [docs](https://github.com/mallardduck/kickflip-docs) for it.


# Warning

Until this repo reaches V1.0.0, this is a work in progress and it ~~may~~ **will** _eat your cat_.  

Before V1 is released this repo may undergo a lot of changes with no backwards compatibility guarantees.

## The Roadmap to V1
- [x] At least 80% test coverage complete
- [x] Full implement Pretty URL build flag
- [ ] Complete the KickflipCLI docs repo
- [x] Review how URLs are generated by Helpers and such (should URLs be relative, or absolute? maybe make it a config option?)
- [x] Determine behavior for static files within sources (copy to build folder or consider better methods)
- [x] Explore idea about mocking traditional laravel routes based on source files (could allow for named routes usage 🤔)
- [ ] Create a starter project
- [ ] Sort out workflows for CHANGELOG.md updates
- [x] Add README.md files to Kickflip-cli and Kickflip-docs
- [x] Hook into NodeJS to ensure required dependencies are installed