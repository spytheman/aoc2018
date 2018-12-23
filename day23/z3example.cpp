#include <iostream>
#include <vector>
#include "z3++.h"

// Find x and y such that: x ^ y - 103 == x * y
void bitvector_example2() {
   std::cout << "bitvector example 2\n";
   z3::context c;
   z3::expr x = c.bv_const("x", 32);
   z3::expr y = c.bv_const("y", 32);
   z3::solver s(c);
   // In C++, the operator == has higher precedence than ^.
   s.add((x ^ y) - 103 == x * y);
   std::cout << s << "\n";
   std::cout << s.check() << "\n";
   std::cout << s.get_model() << "\n";
}


int main(int argc, char **argv)
{
   bitvector_example2();
   return 0;
}
