<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Concept</h1>


<?php include_once 't/head.php';?>
<dt id="10"></dt><h1>Intro</h1>
<P>It is quite common, in maya, to have two different topologies that doesn't match exactly, including in terms of vertices number. If you want to make some blendshapes, for instance, or make automatic this or that action (based on the fact that the vertex number A is always at this position). Unfortunately, Maya doesn't provide a tool to do it. Or to be precise, maya doesn't expose the tool to do it, because it actually exists, present since at least Maya 2010 i think (maybe before, but i started 3d in 2010 =)</P>

<P>So we'll see a small trick to allow you to renumber your vertices without changing your topology, in two (...ish ) steps. The core of this technics consists in using a plugin, delivered with maya and present in your installation folder. We'll have to compile this plugin. If you want to skip the theory of it, you can jump to the second part of the tutorial</P>

<dt id="20"></dt><h1>Part 1</h1>
<dt id="21"></dt><h2>Theory</h2>
<P>As the plugin is not compiled, it is necessary to <a href=https://en.wikipedia.org/wiki/Compiler>compiler</a> it before using. In other words, when we run a piece of code with Maya, it needs first to 'translate' the code in a way that it'll understand and that is usually not human readable. For mel, it's a bit different, as it is the native maya language (mel standing for maya embeded language), but python, for instance, is not understood, as it is, by Maya. It is first compiled to a pyc file (pyc standing for python compiled). Of course, this happens under the hood, and your python script is compiled on the fly when you run your script. But for C++, it is necessary to compile it before (something that you do already if you dev your own plugins with the maya API, but then, i don't know what you're doing here =p). Anyway, the most difficult part will be to compile the plugin.</P>

<P>The main issue when compiling is that it depends a lot on your environment. If you're on mac os, for example, you probably noticed that your plug-in files (the files visibles in the plugin manager) have a '.bundle' extension, whereas on linux, it's '.so' files, and windows uses '.mll' files. In the same fashion, your file will depend on your maya version (a file compiled for maya 2012 will not run on maya 2013). In general, you can compile with the IDE you use (if you're on windows, visual basic does the trick, or same thing with xcode on mac). You can check the amazing work of <a href=http://www.chadvernon.com/blog/>Chad Vernon</a>, he did a great job explaining and debunking the use of the maya C++ API, including explanations on how to compile.
But the main interest of what we'll see here is the fact that we get rid of all the complex setup usually necessary to compile. And we can do that thanks to a <I>MakeFile</I> file, provided by Autodesk, in the plugins folder. This file allow to compile regardless the platform. The idea here is not to go too deep in the details, but instead, do the minimum to have something useable. Considering the fact that every installation is really specific to the environment (existing elements, operating system, and so on), i'll do my best to give you a simple explanation that'll work in most of the situations.</P>

<dt id="22"></dt><h2>Preparing the files</h2>
<P>To sum up, we'll need 3 files, as well as the project itself. The project (i.e. the set of cpp/header files) can be found in your maya install directory, in devkit/plug-ins. Do not hesitate to have a look at all those files if you want to start writing your own plug-ins, you'll find here loads of examples on how to use the API ! Anyway, on my machine, it's :</P>
<?php createCodeX("/Applications/Autodesk/maya2015/devkit/plug-ins/meshReorder");?>
<P>In the same folder, you can see the 'MakeFile' file, which will give all the needed informations to the compiler.
One level above (so in the list of all plugins present in the devkit), you can find the two other files :
buildconfig et buildrules</P>

<P>Let me focus here on one important point : in order to avoid corrumpting Maya's installation files, i copied/pasted all the file we'll work on in a separated folder. Therefore, no matter what we do, we will not modify the original files, and if we want, for any reason, to start again, we'll still have the original files, without the need of re-installing maya ! However, it is absolutely <u>mandatory</u> to keep the exact same structure in your files ! This is how it looks like, on my side :</P>

<?php addImage("01.png", "Arborescence du 'projet'");?>

<P>We're not gonna change MakeFile nor buildrules, which are correct already, thank you Autodesk ! However, we'll have to edit buildconfig, in order to write down the path where Maya has been installed. So open buildconfig with any text editor you like. Around line 35 (it's at line 37, for me), you'll find a line that looks like that :</P>
<?php createCodeX("ifeq ($(MAYA_LOCATION),)
    MAYA_LOCATION = /Applications/Autodesk/maya$(mayaVersion)/Maya.app/Contents
");?>
<P>As you understood, you want to change the given path (which will be different, regarding your OS) with the actual path. So for me, it looks like that :</P>
<?php createCodeX("ifeq ($(MAYA_LOCATION),)
    MAYA_LOCATION = /Applications/Autodesk/maya2015/Maya.app/Contents
");?>
    
<P>Of course, if you're on Windows or Linux, that path will be different, more likely something like C:/Programs/Autodesk/... or /installs/Autodesk/...</P>

<P>Once this file modified, you can save and close it.</P>

<dt id="30"></dt><h1>Part 2</h1>
<dt id="31"></dt><h2>Compilation</h2>

<?php
addTip("Before running the compilation, it is important to precise that in order to compile, you usually need a bunch of libraries. To prevent you from going too far in the configuration and in the technical aspect of the problem, the best is probably to install visual studio (windows), xCode (macOs), or any IDE that comes with the most common libraries, that you'll get for free with the install. There is a small manipulation to do on macOs though. Indeed, on macOs, you'll need the sdk 10.8. Basically, let's say that to plugins for maya, you need this version 10.8, but it became quite difficult to find and download it. So there are two solutions for that :
1 - When you develop directly in xCode, you can choose the sdk 10.9, despite Autodesk recommendations (i never had any issue yet).
2 - When you want to compile something outside of visual studio or xcode and you don't want to search for this 10.8 sdk for ages, you can re-create it by yourself ! If you installed xCode, you should have macOS10.9 sdk and macOS10.10 sdk. You can find them in /Applications/Xcode.app/Contents/Developer/Platforms/MacOSX.platform/Developer/SDKs by default. From here, you can copy/paste the 10.9, and rename it to 10.8 =]");?>


<P>Next step, we want to open a terminal for mac or linux, or a console for windows. Within the terminal, navigate to the meshReorder directory, using the 'cd' command (to change of directory). I haven't used windows for a while, so i'm not sure on how to navigate with the console, but from what i remember, it might be either cd as well, or just the path you want to go to, with no prefix command. A quick google search will help you if needed. Anyway, let's start !</P>

<?php addImage("02.png", "Navigate to the folder where your project is located");?>

<P>Once here, all you have to do is to run 'make', by simply writing it and pressing enter. That will initialize the compilation.</P>

<?php addImage("03.png", "Boooom !");?>

<?php
addTip("If you have an issue with a 'linker' (read what the terminal will output), it's likely due to a bad path to the maya installation, in the buildconfig, that results in the compiler not being able to find Maya.");?>

<P>Done, now you should have a .mll / .so / .bundle file, regarding your OS, which will be seen by maya. So you can now open maya and go to the plug-in manager, then 'browse' to the folder where you compiled your plugin to select, then load it.</P>

<?php addImage("04.png", "");?>
<?php addImage("05.png", "Youhou, our plug-in is visible for Maya !");?>


<dt id="32"></dt><h2>How to use it...</h2>

<P>Great, we have our plugin... then what ?</P>
<P>This plug-in is a maya command. Which means you'll have to run a command, from the script editor, with the correct arguments, if you want to use it</P>

<P>To run our command, you can see in the 'info' part of the plug-in manager, that the command is meshReorder. It expects 3 arguments, 3 vertices of the same face, that will be the new 3 first vertices. Of course, it has to be on the same face... So the syntax, in a mel tab, will look like this :</P>
<?php createCodeX("meshReorder mesh.vtx[23] mesh.vtx[12] mesh.vtx[341];");?>

<P>As with any plug-in (and even more the one developped by Autodesk =p), save your scene before using it ; in case of problem, and if the error hasn't been explicitely handled in the plug-in, Maya will just crash. So if you doesn't give the exact name of arguments, if you make a mistake in the type of the given arguments, or anything that the plug-in is not ready to receive, it'll crash. Moreover, i've read on forums that the very heavy meshs are a problem for the plugin, as it is, even though i never experienced by myself. If i remember correctly what i read, Chad Vernon (him again !) wrote a new version, faster, lighter, with a better error handling, and sent it to Autodesk a few months ago, so they should replace the current version by the new one made by Chad, inch'allah =]
</P>

<P>For people that are not super comfortable with scripting, you can find quite easily on internet an interface to not have to write the command manually. And for people not comfortable with scripting AND with googling stuff, i made you a quick ui that'll do the job for you ! Go at the bottom of this page, or in the 'downloads' section to download the file. As it is using PyQt/PySide, you can use it only with maya2013 or later, or a previous version, provided that you installed PyQt4 on it. Moreover, your plug-in has to be in the maya plugin path, of course, unless you browsed it and enabled it via the plug-in manager</P>

<P>Finally, please do not hesitate to let me know if it doesn't work as expected ; i wrote it pretty quickly, and didn't have a lot of time to test it properly...</P>


<dt id="40"></dt><h1>Conclusion</h1>
<P>As said, and considering the sensitivity of those manipulations, you may have some issues i'm not mentionning here. If so, do not hesitate to post a comment, and we'll try to answer</P>

<P>I think it's also useful to precise that the plug-ins availables in devkit are - as far as i know - availables for education purpose, and shouldn't be used in production unless it is absolutely necessary. What makes meshReorder less critical than some others is the fact that it doesn't let any dirtyness in your scene. It's not a node, but a command, that is used at one point, and disappears after. I strongly recommand to be careful when using it, though. My friend who created 3dbunk with me and I used it quite a lot in production on a feature film, and we didn't experience any issue, but you never know !</P>



