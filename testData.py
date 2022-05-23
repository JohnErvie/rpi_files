import serial
import time
import pymysql

# for collecting data
ser = serial.Serial('/dev/ttyUSB0', 115200, timeout=0.25)
ser.reset_input_buffer()

firstVal = 0

import pickle # This library is for saving or load the model into a file
from datetime import * # this library is for the current time

with open(r"roomData", "rb") as input_file: # defining a input_file variable as the filename of the current model with a read parameter
    model = pickle.load(input_file) # loading the model and define as model variable
    
#database connection
connection = pymysql.connect(host="localhost", user="admin", passwd="password", database="pd_database")
connection.autocommit = True
cursor = connection.cursor()

while True:
    dateToday = datetime.now() # getting the current and declare as datetime variable
    dateNow = date.today() # today's date
    TimeNow = (datetime.time(datetime.now())) # current time
    if ser.in_waiting > 0:
        line = ser.readline().decode('utf-8').rstrip()
        split = line.split(",")
        
        while("" in split) :
                split.remove("")
        
        #print(len(split))
        
        data = []
        
        if len(split) > 1 and len(split) <= 13:
                splitLen = int(len(split))
                
                
                data.append([])
                
                for i in range(splitLen):
                        data[0].append(split[i])
                        
                
        elif len(split) > 13 and len(split) <= (13*2):
                splitLen = int(len(split)/2)
                
                data.append([])

                
                for i in range(splitLen):
                        data[0].append(split[i])
                        
                        
                data.append([])
                for j in range(splitLen, splitLen*2):
                        data[1].append(split[j])

                        
        elif len(split) > (13*2) and len(split) <= (13*3):
                splitLen = int(len(split)/3)
                
                data.append([])
                
                for k in range(int(splitLen)):
                        data[0].append(split[k])
                        
                data.append([])
                for l in range(int(splitLen), int(splitLen*2)):
                        data[1].append(split[l])
                     
                data.append([])
                for m in range(int(splitLen*2), int(splitLen*3)):
                        data[2].append(split[m])
        
        elif len(split) > (13*3) and len(split) <= (13*4):
                splitLen = int(len(split)/4)
                
                data.append([])
                
                for k in range(int(splitLen)):
                        data[0].append(split[k])
                        
                data.append([])
                for l in range(int(splitLen), int(splitLen*2)):
                        data[1].append(split[l])
                    
                data.append([])
                for m in range(int(splitLen*2), int(splitLen*3)):
                        data[2].append(split[m])
                        
                data.append([])
                for n in range(int(splitLen*3), int(splitLen*4)):
                        data[3].append(split[n]) 
        if (firstVal == 1):
                for i in range(4):                         
                        if (data[i][2] != ' NAN'):
                                #insertData = "INSERT INTO data(sensor,datetime, voltage, current, power, energy, frequency, pf) VALUES('{}','{}', {},{},{},{},{},{})".format(data[i][0], dateToday, float(data[i][2]),float(data[i][4]), float(data[i][6]), float(data[i][8]), float(data[i][10]), float(data[i][12]))
                                #cursor.execute(insertData)
                                #connection.commit()

                                # using now the model 
                                Voltage_score = model.decision_function([[float(data[i][6])]]) # Computing the Average anomaly score of PC variable of the base classifiers

                                Voltage_anomaly_score = model.predict([[float(data[i][6])]]) # Predict if a particular sample is an outlier or not (anomaly or normal)

                                if Voltage_anomaly_score == -1: 
                                        PC_status = 'Anomaly' # if the Voltage_anomaly_score is equal to -1 then this is a Anamaly

                                else:
                                        PC_status = 'Normal' # if the Voltage_anomaly_score is equal to 1 then this is a Normal Data

                                # queries for inserting values
                                insertPC = "INSERT INTO pc_table(rpi_id, sensor, datetime, date, time, power_consumption, power_consumption_score, power_consumption_anomaly_score, status) VALUES({}, '{}', '{}', '{}', '{}', {}, {}, {}, '{}');".format(3, data[i][0], dateToday, dateNow, TimeNow, float(data[i][6]), float(Voltage_score[0]), int(Voltage_anomaly_score[0]), PC_status)

                                #executing the quires
                                cursor.execute(insertPC)
                                connection.commit()
                                print(data[i][0])
                                #print(data)
        else:
             firstVal += 1   
	#time.sleep(0.5)
        
