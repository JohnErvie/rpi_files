#!/usr/bin/env python3
import serial

if __name__ == '__main__':
    port="ttyUSB0"
    ser = serial.Serial('/dev/'+port, 115200, timeout=1)
    ser.reset_input_buffer()
    while True:
        if ser.in_waiting > 0:
            line = ser.readline().decode('utf-8').rstrip()
            split = line.split(",")
			
            split.insert(0,port)
            split.remove('')
            print(split)
			#print("")
		
			
			
