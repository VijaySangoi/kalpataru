import can

print_listener = can.Printer()
bus = can.Bus(interface='socketcan',channel='vcan0',receive_own_message=True)
print_listener = can.Printer()
can.Notifier(bus,[print_Listener])