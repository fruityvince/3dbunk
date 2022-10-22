<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Introduction</h1>

<P>When one start developping for maya, one usually follow a linear path : we start with the script editor, then we realise it's quite rigid on many aspects. So we try notepad++. We find that we can tweak the colors, that indentations are automatic, that we have a better auto-completion, and so on... And usually, the next step is switching to a proper IDE, or <?php what("Integrated Development Environment", "https://en.wikipedia.org/wiki/Integrated_development_environment");?>. And in this tutorial, as you probably guessed, we'll focus in one in particular, Eclipse</P>

<P>The interest of a complete IDE is that you can work within a project instead of a file, so you end up with a full project more module-based, hence more object-oriented =] Moreover, a bunch of tools will help you improving your efficiency and working in better conditions, or even doing things that you couldn't  do with a basic text editor or with maya script editor. Finally, Eclipse can communicate directly with maya ! No longer need to save your script to source it afterwards in maya !</P>


<P>Well, you get the idea, to work with a real IDE is the way of working ! Just so you know, there are plenty of IDE (see the end of the article for examples), so i strongly recommand that once you get the concept with eclipse, you try the others !</P>

<P> To summarize, here are the steps we'll follow :
<UL>
<LI>Download and installation of Eclipse</LI>
<LI>Download and installation of PyDev</LI>
<LI>Set Maya</LI>
<LI>Set Python for Eclipse</LI>
</UL>
</P>

<dt id="20"></dt><h1>Eclipse installation</h1>

<P>First things first, you need... Eclipse =] You can download it on www.eclipse.org. Just like most of the IDEs, Eclipse can be used for many langages, and by default, it comes 'empty', which means it's up to the user to install the packages he'll need regarding his favorite languages (java, c++, php, python, etc). However, you'll see by yourself on the website that you can already take some 'language-ready' distribution depending on your needs. I started with the C++ version for personnal reasons, but you can take the want you want, as we'll come back later on what we need for python and maya !</P>

<P>Please also notice that Eclipse is a standalone. So no need to install it, you can just copy/paste the folder where you want. Usually, i copy it in C:\Program Files, just to stay consistent, but put it where you fancy</P>

<P>At this point, you can already double-click on the eclipse.exe file (located in your Eclipse folder) to open your IDE. Not that you may have an error message not really understandable about Java. Indeed, Eclipse needs some java libraries to run. I don't know if the <?php what("JRE", "http://fr.wikipedia.org/wiki/Environnement_d%27ex%C3%A9cution_Java");?> is enough, i always install the <?php what("JDK", "http://fr.wikipedia.org/wiki/Java_Development_Kit");?>, and it fix the issue. <?php what("At the time when i write those lines, you can find it here", "http://www.oracle.com/technetwork/java/javase/downloads/index.html");?>


<dt id="30"></dt><h1>Installation de PyDev</h1>

<P>As i told you, Eclipse is given 'empty' by default (or with a few presets regarding the distribution you downloaded). So we'll need to download some tools in order to use Eclipse with Python.
The python package for eclipse is named PyDev. To get it, you can either navigate to the PyDev project website directly, or use a very handy tool that Eclipse comes with, called the Market Place, and that you can find in Help > Eclipse Marketplace. This is what i use !</P>

<P>Once in the market place, in the text field, write 'PyDev". Among several results, you should find "PyDev - Python IDE for Eclipse"<P>

<?php addImage("00.jpg", "La recherche effectuée via le Marketplace");?>

<P>Click then on the "install" button. The next window will ask you to choose the packages you want to install. No need to detail, just keep the default values</P>

<?php addImage("01.jpg", "");?>

<P>All you have to do now is to confirm in order for Eclipse to download and install automatically everything for you ! Such a nice guy, this Eclipse ! (Of course, when asked, accept the terms of the licence if you agree)</P>

<?php addImage("02.jpg", "");?>

<P>Finally, you're asked to restart Eclipse</P>


<dt id="40"></dt><h1>Setting the bridge to Maya</h1>

<P>Now, yor Eclipse has PyDev, that allow you to develop in python. Now, we'll focus on creating a bridge with Maya. That will be done in two steps. First we'll set Eclipse, then we'll 'open the gates' on Maya's side.</P>

<dt id="41"></dt><h2>Eclipse side</h2>

<P>You can find the plugin we need on <?php what("Creative Crash, here", "http://www.creativecrash.com/maya/downloads/applications/syntax-scripting/c/eclipse-maya-editor");?>.
You need to register in order to download stuff, but subscription is free ( and i strongly encourages you to do it, if ever it's possible that you don't have an account yet, this website is a huge reference !)</P>

<P>You'll download a file of type .jar (for me, eclipseMayaEditor_2015.0.0.201405052317.jar)</P>

<P>Once again, different methods. Personnaly, i copy this .jar file directly in the Eclipse install folder (depends on where you pasted your folder). For me, it's C:\Program Files\eclipse\plugins. Once again, let's restart eclispe once it's done</P>

<P>All we have to do now is to set the correct compiler and add maya's auto-compleation. As you know, when maya 'reads' some Python code, it compiles it (which means that you have some .py files in your script folder, but also some .pyc files (pyc stands for python compiled), that are compiled files). That means maya uses a compiler, one way or another ! So what we want is to use Maya's python compiler !</P>

<P>Go to Window>Preferences to open the preferences window... From here, navigate to PyDev>Interpreters>Python Interpreter</P>

<?php addImage("03.jpg", "");?>

<P>Click then on the 'New...' button in the upper left corner to configure a new interpreter. In the next window, name it how you want (maya2013 for me), and memorize that name, we'll need later on ! Then, browse the interpreter located by default in C:\Program Files\Autodesk\Maya2013\bin\mayapy.exe, then click "ok"</P>

<?php addImage("04.jpg", "");?>

<P>In the next window, Eclipse suggest a bench of directories containing the libraries needed for compiling, by default. Just click "ok" again</P>

<?php addImage("05.jpg", "");?>

<P>Voilà, our interpreter is set :</P>

<?php addImage("06.jpg", "");?>

</P>Let's now add auto-completion. For those who don't know, autocompletion is the fact that start writing cmds.parentC, for example, and the IDE will suggest automatically cmds.parentConstraint(). Pretty handy, especially when you don't know all the commands by heart !</P>

<P>Go to the Predefined tab, in the lower part of the same window, then click again on 'new' (the one in the lower part, this time, attached to the 'predefined tab !')</P>

<?php addImage("07.jpg", "");?>

<P>In the next window, Eclipse asks to select a folder. It expects the folder that stores the auto-completion list for maya. That folder is located, by default, in C:\Program Files\Autodesk\Maya2013\devkit\other\pymel\extras\completion\, and it's the pypredef folder.
So select this folder in your Eclipse window, then press 'ok'</P>

<?php addImage("08.jpg", "");?>

<P>Finally, click 'ok' to quit Eclipse prefs, then restart Eclipse (no obligation though, i just prefer to do so, just to be on the safe side). And that's it for the Eclipse part</P>

<dt id="42"></dt><h2>Maya side</h2>

<P>You now want to open a port in order for Eclipse to be able to 'communicate' with Maya. The maya command to use to open a port is commandPort, and the port you want to open is 7720. Copy and past the following code in a python tab, in the script editor :</P>


<?php createCodeX("import maya.cmds as cmds
if cmds.commandPort(':7720', q=True) !=1:
    cmds.commandPort(n=':7720', eo = False, nr = True) 
");?>

<P>You can save this piece of code as a button in a shelf in order to run it, or add it directly to your userSetup.py so that it'll be run automatically each time you run a new instance of Maya. This is not the purpose of this tutorial though, so i won't detail further, but keep in mind that everything you write in your userSetup.py (or userSetup.mel if you write in mel) is executed at each start of maya.</P>

<P>And we're done with the 'installation' part itself ! We'll now go quickly through the basic options of Eclipse (i insist on the 'basic'!
When starting eclipse, you can see that the interface is pretty light !</P>

<?php addImage("09.jpg", "");?>

<P>From here, you have two options : you import an existing project (via file > import > general > existing projects into workspace), but that implies that you already work with Eclipse. So more probably, you'll want to create a new project. To do so, go to File > New > Project. Then, choose PyDev Project.</P>

<?php addImage("10.jpg", "");?>

<P>The next window asks you to name your project, and to choose an interpreter for this project. There are a few of other options that you'll want to try later, it's pretty straight forward</P>

<P>Name your project as you want, but of course remember to change the interpreter ! Remember, i named mine maya2013, so it's under this name that i find it back in my list of interpreters. But it's maybe named differently for you, regarding the name you gave a few steps ago.</P>

<?php addImage("11.jpg", "");?>

<P>Once you're project is created, Eclipse should detect that it's a python project and asks you to open the correct perspective (i.e. to lay things out to something it considers to be optimized for python). Saying 'yes' now is the same thing than going to Window > show view > PyDev package explorer : a new window pops in your workspace, where you can see all your project files. It's a bit like the outliner in maya</P>

<P>I'll let you explore the interface by yourself, for now we'll just create a file and execute it to be sure everything is set properly.
In the PyDev Package Explorer window, unfold your project to see it contains only the interpreter, for now (we have to start somewhere =) :</P>

<?php addImage("12.jpg", "");?>
























<P>Right click on your project, then new>file . Name it as you want, and remember to add the .py extention, so that it's automatically detected as a python file :</P>

<?php addImage("13.jpg", "");?>

<P>Double click on your file to open it, they write the following code :

<?php createCodeX("import maya.cmds as cmds
cmds.warning( 'hello World ! ' )
");?>


<P>We will now send this to Maya. Make sure you have the right port opened (see the part above if needed).

If the settings of auto-completion went well, you should be able to see auto-completion at this stage (i can't show it to you as window's screen shot application is so great... =).
If not, go back to the stage where we set the auto-completion, and if it sill doesn't work, feel free to expose your problem in the comments.
If all is okay, if you start writing cmds.war, Eclipse should suggest cmds.warning() !</P>

<P>
Anyway, let's see how to send our awesome script to maya. If the instalation of creativeCrash's plugin worked, you should have a bunch of extra buttuns in eclipse's toolbar :

<?php addImage("14.jpg", "");?>
<?php addImage("15.jpg", "");?>


The buttun at the very right, wich looks like an electric outlet, allows you to connect Eclipse to Maya. If you click on it, and go back to Maya, your script editor should send back :


<?php addImage("16.jpg", "");?>

Then, you only have to run your script, with one of the three left buttuns (mouse over the tool and read the tip for more details) !
<P>

<?php addImage("17.jpg", "");?>
   
<dt id="50"></dt><h1>Conclusion</h1>

<P>So, that was for Eclipse's installation. However, this is only the begining. I warmly invite you to customize your color setting by downloading, using the marketPlace for instance, extra packages to manage your syntax color, to choose your keyboard shortcuts, etc.. !

<P>Moreover, what's here proposed in terms of configuration is rather 'generalist', and you could have different situations to deal with (regarding softwares already installed on your machine for example), but it would be as difficult for me as boring for you to cover all diferent settings possible. So don't hesitate to tell about the problem you encountered in coments =)</P>

<P>Finally, we covered the installation of Eclipse here because it is the most famous IDE for beginners coming from Maya, from my 
experience. But you should definetly check out some different editors once you're familiar with the concept ! I don't know all of 
them, but for those i tried already, i can tell you that 

<?php what("PyCharm", "https://www.jetbrains.com/pycharm")?> is super cool as well (and doesn't deal with python ONLY !) 
<?php what("Sublime", "https://www.sublimetext.com/")?> is quite nice, especially to deal with mel and c++, and it's highly customizable. 
<?php what("jEdit", "http://www.jedit.org/")?> is know for being cool for MEL, although i never tried it, and 
<?php what("Atom", "https://www.sublimetext.com/")?> is super cool as well, although it's quite new (if i'm correct, it's developped by the git team). 

The idea is always pretty much the same, and the tool doesn't really matter as long as you feel comfortable with it. 
I personnaly use Atom and Vim at home on mac os, and Sublime and vim at work on linux. 
That's it, feel free to post in the comments section if something went wrong with your installation and need some help </P>
