class Solution {
    public int myAtoi(String s) {
        s = s.trim();
        int len = s.length();

        if (len == 0) {
            return 0;
        }

        long num = 0;
        int index = 0;
        int sign = 1;

        if (s.charAt(0) == '-') {
            sign = -1;
            index++;
        } else if (s.charAt(0) == '+') {
            index++;
        }

        while (index < len) {
            char ch = s.charAt(index);

            if (ch < '0' || ch > '9') {
                break;
            }

            num = num * 10 + (ch - '0');

            if (sign == 1 && num > Integer.MAX_VALUE) {
                return Integer.MAX_VALUE;
            }

            if (sign == -1 && -num < Integer.MIN_VALUE) {
                return Integer.MIN_VALUE;
            }

            index++;
        }

        return (int) num * sign;
        
    }
}
