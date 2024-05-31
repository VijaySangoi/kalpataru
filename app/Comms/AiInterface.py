from transformers import pipeline
import sys

class php_transformer:
    def __init__(self,*args):
        self.args = args[0]
        try:
            self.transform()
        except Exception as e:
            print(e)
    def transform(self):
        qy = pipeline(self.args[1],self.args[2])
        print(qy(self.args[3]))
arg = sys.argv
exe = php_transformer(arg)
