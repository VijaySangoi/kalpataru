import io
import os
import sys
import time
import serial
from datetime import datetime

class InterfaceSerial:
    def __init__(self,*args):
        print("reached here")
        self.args = args[0]
        self.file()
        try:
            self.ser = serial.Serial(self.args[1],self.args[2],timeout=1)
            self.sio = io.TextIOWrapper(io.BufferedRWPair(self.ser,self.ser))
            self.once()
        except Exception as e:
            print(e)
        pass

    def once(self):
        while(1):
            log_f = open(self.log_filepath,"a")
            line = self.sio.readline()
            timestamp = "["+self.now.strftime("%d-%m-%Y, %H:%M:%S")+"]"
            if not(line == ""):
                log_f.write(timestamp+line+"\n")
            log_f.close()

            pipe_f = open(self.pipe_filepath,"r")
            line = pipe_f.read()
            self.ser.write(bytes(line,"utf-8"))
            pipe_f.close()
            pipe_f = open(self.pipe_filepath,"w")
            pipe_f.close()
            
            self.now = datetime.now()

    def file(self):
        self.now = datetime.now()
        self.log_filename = self.args[1]+"-"+"log"+"-"+str(self.now.strftime("%d"))+"_"+str(self.now.month)+"_"+str(self.now.year)+".txt"
        self.pipe_filename = self.args[1]+"-"+"pipe"+"-"+str(self.now.strftime("%d"))+"_"+str(self.now.month)+"_"+str(self.now.year)+".txt"
        self.log_filepath = "app/Comms/__pipe/"+self.log_filename
        self.pipe_filepath = "app/Comms/__pipe/"+self.pipe_filename
        if not(os.path.exists(self.log_filepath)):
            print("log file created")
            open(self.log_filepath,"w")
        if not(os.path.exists(self.pipe_filepath)):
            print("pipe file created")
            open(self.pipe_filepath,"w")

arg = sys.argv
inf = InterfaceSerial(arg)