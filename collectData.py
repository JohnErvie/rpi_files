import sys
import os
import serial
from datetime import *
import time 
import pymysql
import random

#database connection
connection = pymysql.connect(host="localhost", user="admin", passwd="password", database="pd_database")
connection.autocommit = True
cursor = connection.cursor()

# for collecting data
port = "ttyUSB0"
ser = serial.Serial('/dev/' + port, 115200, timeout=1)
ser.reset_input_buffer()

while True:
    dateToday = datetime.now()
    dateNow = date.today()
    TimeNow = (datetime.time(datetime.now()))

    if ser.in_waiting > 0:
        line = ser.readline().decode('utf-8').rstrip()
        split = line.split(",")
        split.insert(0,port)
        split.remove('')
        print(dateToday,split)
        
        port1 = split[0]
        power1 = float(split[6])
        
        #power1 = float(random.uniform(1.5, 4.5))
        #power1 = 0
        print(power1)
        
        insertData = "INSERT INTO data(port,datetime, voltage, current, power, energy, frequency, pf) VALUES('{}','{}', {},{},{},{},{},{})".format(split[0], \
            dateToday, float(split[2]), float(split[4]), power1, float(split[8]), float(split[10]), float(split[12]))
        cursor.execute(insertData)
        connection.commit()
        
        time.sleep(0.5)
