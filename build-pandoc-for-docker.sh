#/bin/sh

docker run --rm -it -v $(pwd)/bin:/root/.local/bin fpco/stack-build bash '/root/.local/bin/build.sh'
