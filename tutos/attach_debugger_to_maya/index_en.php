<?php include_once 't/head.php';?>


<dt id='10'></dt><h1>General idea</h1>
<P>The idea behind the debugging is to be to able understand what is going wrong when maya freezes / spends too much time in a particular operation / is stuck in an infinite loop / etc... So basically, this becomes helpful in the situation where you can no longer interact with maya.</P>


<P>One or two precisions before we start : this a pretty advanced technique, not necessarily for everybody, and not needed on a daily basis. Moreover, i am using for this tutorial a debugger called GDB. I think you can find some others, but i don't know them. Finally, i'm using it with Linux as well as Mac OS (seems native on my linux distribution, and on mac os, a quick 'homebrew' should help you to install it), but i have no idea on how to use it with Windows.</P>



<dt id='11'></dt><h1>Get maya pID</h1>

<P>In the terminal, run 'top'. That will display all the softwares currently running. What you want to get here is the pID attached to the maya you want to debug. Once you have it, just hit 'q' to exit.</P>

<?php addImage("01.jpg", "list of all the process IDs");?>


<dt id='12'></dt><h1>Launch the debugger</h1>

<P>Still in the terminal, run :</P>

<?php createCodeX("gdb -p numId");?>

<P>where numID is the ID of your maya process, that we get in the first step.</P>

<?php addImage("02.jpeg", "");?>

<P>From here, the terminal will return a bunch of un-understandable lines. Those lines are everything maya did since the beginning of your session (the beginning being the lower part, and the most recent operations the higher part)</P>


<dt id='13'></dt><h1>Understand the debugger</h1>


<P>To make it more readable, you probably want to run (within the debugger) backtrace (or bt).</P>

<?php addImage("03.jpg", "Let's get more details with backtrace !");?>

<P>Backtrace will display in a more friendly way everything that has been done since the beginning. It is probably more obvious for people familiar with the maya API, but in general, it kind of makes sense already, if you take time to read what is written (I'm sure everybody understands what TdependNode::getPlugValue may do, for instance !). The next step in understanding what happens is to use some extra python libraries to unpack that !</P>

<dt id='14'></dt><h1>Using python to unpack the debugger messages</h1>

<P>It is necessary to have an extra library, called libpython, to do it, that can be found online (python svn). You can also use the one provided here, in the download section.</P>

<P>In order for gdb to be aware of this library, you have to append it to the gdb python compiler. To do so, just run this python compiler within gdb, and append the path to libpython.py to this session of python :

<?php addImage("04.jpeg", "let's enhance our gdb python interpreter !");?>

<?php createCodeX("python
import sys
sys.path.insert(0, '/path/to/your/libpython/folder')
import libpython");?>
<P>Then, hit ctl+d to run the code you just wrote and exit.</P>

<P>Now, you're ready to re-run backtrace, with all the python inputs in it.</P>

<P>You have to be aware that as soon as you attach a debugger to your maya instance, this maya instance will be 'frozen', as long as you don't detach your debugger of it. So don't be surprise if an operation lasts forever if you have a debugger attached on your maya =]
</P>

