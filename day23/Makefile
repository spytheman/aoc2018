CXX = g++
SOURCES=$(shell find . -name '*.cpp')
CPPFLAGS=-std=c++14 -Wall -pedantic -Ofast -march=native -mtune=native -mavx
TARGETS=day23 inrange.o

all: day23

day23: day23.cpp inrange.o

clean:
	rm -rf TAGS $(TARGETS)

TAGS:
	/opt/uctags/bin/ctags -e -o TAGS $(SOURCES)
