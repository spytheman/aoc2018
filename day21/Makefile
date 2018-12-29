CXX = clang++
SOURCES=$(shell find . -name '*.cpp')
CPPFLAGS=-std=c++14 -Wall -Ofast -march=native -mtune=native -mavx
TARGETS=day21 runElfInput day21TranspiledElfProgram day21TranspiledElfProgram_Direct day21TranspiledElfProgram_Switch

all: $(TARGETS)

day21: day21.cpp

runElfInput: runElfInput.cpp

day21TranspiledElfProgram: day21TranspiledElfProgram.cpp
day21TranspiledElfProgram_Direct: day21TranspiledElfProgram_Direct.cpp
day21TranspiledElfProgram_Switch: day21TranspiledElfProgram_Switch.cpp

################################################################

clean:
	rm -rf TAGS $(TARGETS)

TAGS:
	/opt/uctags/bin/ctags -e -o TAGS $(SOURCES)

generateCPPFromInput: input 
	../elfasm2c.php input > elfProgram.cpp
