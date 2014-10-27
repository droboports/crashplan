INCLUDE = -I. -Iinclude -I$(JAVA_INCLUDE) -I$(JAVA_INCLUDE)/linux
OBJECTS = jtux_clock.o jtux_dir.o jtux_file.o jtux_network.o \
          jtux_posixipc.o jtux_process.o jtux_sysvipc.o jtux_util.o

.c.o:
	$(CC) -Wall -D_REENTRANT -D_THREAD_SAFE -DLINUX -std=c99 -fPIC $(CFLAGS) $(INCLUDE) -c $(<) -o $(@)

all: $(OBJECTS)
	$(CC) -o libjtux.so -shared -lrt -lnsl $(OBJECTS)

clean:
	rm -f *.o

distclean: clean
	rm -f libjtux.so
