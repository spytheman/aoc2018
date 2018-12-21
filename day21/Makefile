SOURCES=$(shell find . -name '*.cpp')
CPPFLAGS=-std=c++14 -O2
TARGETS=day21 runElfInput

all: $(TARGETS)

day21: day21.cpp

runElfInput: runElfInput.cpp

################################################################

clean:
	rm -rf TAGS $(TARGETS)

TAGS:
	/opt/uctags/bin/ctags -e -o TAGS $(SOURCES)

generateCPPFromInput: input 
	../elfasm2c.php input > elfProgram.cpp
