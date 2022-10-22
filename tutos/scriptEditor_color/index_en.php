<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Introduction</h1>
<p>This tutorial will be a nice occasion to have a look to PyQt in a very light way, and how we can associate its strength with Maya,
our aim is the following ; the Command History of Maya is a bit sickly and pale so we're going to add some colors to make it more readable.</p>
<p>We'll first try to identify the scriptEditor's window, then we'll manage to get the correct <b>QTextEdit</b> child object, finally we'll
assigne a <b>QSyntaxHighlighter</b> to it. Regular expressions basic knowledge may be useful to create your own coloring, let's start !</p>
<?php addTip("These two websites are great help if you want to learn more about regular expressions !<br><a href='https://regex101.com/'>Online Regex Tester</a><br>
		<a href='https://en.wikipedia.org/wiki/Regular_expression'>Wikipedia's article</a><br>");?>

<dt id="20"></dt><h1>PyQt</h1>
<dt id="21"></dt><h2>Identify our aim</h2>
<p>So we're going to search the PyQt widget we want thank to the <b><i>wrapinstance</i></b> function from <b>sip</b>, aka
<b><i>wrapInstance</i></b> for <b>shiboken</b> in completion with the wonderful class  
<b><i>MQtUtil</i></b> from <b>OpenMayaUI</b> in order to search inside Maya the widgets we are looking for. First let's find the 
Maya main window, which will be an easy shot;</p>
<?php addNote("The <b>wrapInstance</b> function needs a unique 'id' corresponding to the QWidget, and the main class of the widget,
for our current example we're looking for the main window, so the class will be a QWidget");
?>
<br>
<?php 
createCodeX("from PySide import QtCore, QtGui
from shiboken import wrapInstance
from maya.OpenMayaUI import MQtUtil

maya_win = wrapInstance(long(MQtUtil.mainWindow()), QWidget)
");?>
<p>Fine, now we can loop through children to find the scriptEditor window, several ways of approach can be used, we're just going
to find all the children which contains '<i>script</i>' in their object name, I'm pretty confident this will be the case for our
script Editor window =)</p>

<?php createCodeX("for child in maya_win.children():
    if 'script' in child.objectName():
        print 'CHILD => %s' % child.objectName()");
?>
<p>One shot ! You should see something like that ;</p>
<?php createCodeX("# CHILD => scriptEditorPanel1Window");?>
<p>We now have the name of our scriptEditor window and especially the corresponding object. Our second task will be to iterate inside its numerous
children to find the <b><i>QTextEdit</i></b> of the History. The method is quite the same as above, except we need to do this recursively in all
children and children's children to find all the <b><i>QTextEdit</i></b> instances to get the correct object's name. We spare you the pain of the 
search, so the name <b><i>QTextEdit</i></b> we're looking for is named <b>cmdScrollFieldReporter1</b>, take note that this widget have a unique
name, and that a number is automatically added at the end. This means we could meet <b>cmdScrollFieldReporter4</b>.</p>
<p>Thank to the wonderful class <b>MQtUtil</b> we can now access to the widget using the function <b><i>findControl</i></b>
<?php createCodeX("script_stdout = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter1')), QTextEdit)");?>
<p>Considering the fact that we don't now exactly the index of the widget's name we're going to use a little while loop to find the correct occurence ;</p>
<?php createCodeX("i = 1
while i:
    try:
        se_edit = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter%i' % i)),
                                                        QtGui.QTextEdit)
        break 
    except TypeError:
        i += 1")?>
<p>Alright ! Now we have our widget we can work on the coloring</p>
<dt id="22"></dt><h2>The QSyntaxHighlighter</h2>
<p>Applying the <b><i>QSyntaxHighlighter</i></b> can be achieved very simply be feeding him with a parent as first argument, like below ;</p>
<?php createCodeX("class StdOut_Syntax(QSyntaxHighlighter):
    def __init__(self, parent):
        super(StdOut_Syntax, self).__init__(parent)")?>
<p>PyQt use the function <b>highlightBlock</b> to handle the syntax coloring, this function needs an input text - provided internally by the QTextEdit - 
in which we'll iterate for each regular expression we want to colorize, define a <b>QTextCharFormat</b> for the expression with the function <b>setFormat</b>,
here is a simple example ;</p>
<?php createCodeX("class StdOut_Syntax(QSyntaxHighlighter):
    def __init__(self, parent):
        super(StdOut_Syntax, self).__init__(parent)
    
    def highlightBlock(self, text):
        color = QColor(255, 125, 160)
        pattern = QRegExp(r'^//.+$')        # regexp pattern
        keyword = QTextCharFormat()
        keyword.setForeground(color)         # defining the aspect
        index = pattern.indexIn(text)
        while index >= 0:
            # loop through the text to find matches
            len = pattern.matchedLength()   # length of the match
            self.setFormat(index, len, keyword) # we apply the format to the match
            index = pattern.indexIn(text, index + len)
        self.setCurrentBlockState(0)")?>
<?php addNote("The regular expression used above can be 'humanly translated' as ; <br>
        <b>If the line starts with '//' until the end of the line</b><br>
        <b>^</b>  means the start of the line<br>
        <b>.+</b> greedy expression match a maximum amount of characters, the '.' means any character<br>
        <b>$</b>  means the end of the line");?>
<p>Applying this class to our widget <b>cmdsScrollFieldReporter</b> with the following code we should get ;</p>
<?php createCodeX("from PySide.QtGui import *
from PySide.QtCore import *
from shiboken import wrapInstance
from maya.OpenMayaUI import MQtUtil

class StdOut_Syntax(QSyntaxHighlighter):
    def __init__(self, parent):
        super(StdOut_Syntax, self).__init__(parent)
        self.parent = parent
    
    def highlightBlock(self, text):
        color = QColor(255, 125, 160)
        pattern = QRegExp(r'^//.+$')        # regexp pattern
        keyword = QTextCharFormat()
        keyword.setForeground(color)         # defining the aspect
        index = pattern.indexIn(text)
        while index >= 0:
            # loop through the text to find matches
            len = pattern.matchedLength()   # length of the match
            self.setFormat(index, len, keyword) # we apply the format to the match
            index = pattern.indexIn(text, index + len)
        self.setCurrentBlockState(0)

def wrap():
    i = 1
    while i:
        try:
            se_edit = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter%i' % i)),
                                                            QTextEdit)
            # we remove the old syntax and raise an exception to get out of the while
            assert se_edit.findChild(QSyntaxHighlighter).deleteLater()
        except TypeError:
            i += 1       # if we don't find the widget we increment
        except (AttributeError, AssertionError):
            break
    return StdOut_Syntax(se_edit)
wrap()", "Working color syntaxer class", true)?>
<p>This means each time we'll meet the regular expression of "<b>a line starting with // until the end of the line</b>" will get a red color ! Thus ;</p>
<?php addImage("01.jpg", "We see the light =)")?>
<?php addNote("A QTextEdit accepts only one QSyntaxHighlighter associated, if a second one is linked to the QTextEdit, result may be unpredictable,
so the previous QSyntaxHighlighter must be removed from the QTextEdit children everytime you want to refresh it completely !");?>
<p>Now let's have a look how to "link" our <b>QSyntaxHighlighter</b> to Maya !</p>

<dt id="30"></dt><h1>Linking intrinsically to Maya</h1>
<dt id="31"></dt><h2>Starting at launch</h2>
<p>The execution of customized scripts when Maya starts can be easily achieved with the file <b>userSetup.py</b> in the folder Maya/scripts in your Documents,
if the file doesn't exist, create it.</p>
<p>We will copy our awesome script in a new file named <b><i>syntax.py</i></b> in this folder, then we need to edit our <b>userSetup.py</b> file to
add the following line ;</p>  
<?php createCodeX("import syntax")?><br>
<?php addTip("The use of the Maya's cmds function <b>evalDeferred</b> is often recommended if you want to differ the execution of scripts until 
Maya is 'available'.<br><br>Our example is a simple import statement so we don't need to do that =) !");?>
<p>Alrigh, then if you open your scriptEditor, and type </p><?php createCodeX("syntax.wrap()")?><p> our Command History <b>QTextEdit</b> will
 take some some colours !</p>
<dt id='32'></dt><h2>A bit of hacking =) !</h2>
<p>Now we have our function which colorize the Command History of Maya, we need to link it to Maya so each time the scriptEditor opens, the 
<b>QSyntaxHighlighter</b> will be parented to the corresponding <b>QTextEdit</b> ! Because everytime the window is closed, the Command History 
<b>QTextEdit</b> widget is deleted, so is our <b>QSyntaxHighlighter</b> !
<p>If we turn on the option "<b>Echo All Commands</b>" in the scriptEditor we'll see that the command <b><i>scriptEditor;</i></b> is
called when opening the window. After a search in <b>Window &rarr; Settings/Preferences &rarr; Hotkey Editor</b> under the <b>Window</b>
section we find that the executed script for this function is ;</p>
<blockquote>if (`scriptedPanel -q -exists scriptEditorPanel1`) { scriptedPanel -e -to scriptEditorPanel1; showWindow scriptEditorPanel1Window; selectCurrentExecuterControl; }else { CommandWindow; }</blockquote>
<p>Unfortunately these 'inside' functions in Maya aren't editable, and even if they were, this will only change the script for the keyboard shortcut,
not the other ways to open the window, like the little button <img src='t/scriptEditor_color/img/se.png'> for instance, will continue to execute the function
quoted above.</p>
<p>So we're going to have a look on the internal Maya files, with the secret hope to find this function in some .mel script, using a software like 
<?php dl("Notepad ++", "https://notepad-plus-plus.org/download")?> we will be able to search inside all Maya's files and find the one which contains our command.</p>
<p>A little - and efficient - search indicates that the file <b>defaultRunTimeCommands.mel</b> in the folder <b>scripts/startup</b> inside the Maya folder
contains what we are looking for.<br>
The file is really huge, and the line we want differs between Maya versions, for Maya 2013 that line is the 4096th, for Maya 2014 that's the 4228th, 
simply search <b>scriptEditor;</b> in the file should bring you to correct line =) !</p>
<p>We just need to add a simple MEL command at the end of the Maya command, from ;  
<blockquote>-command    ("if (`scriptedPanel -q -exists scriptEditorPanel1`) { scriptedPanel -e -to scriptEditorPanel1; showWindow scriptEditorPanel1Window; selectCurrentExecuterControl; }else { CommandWindow; }")</blockquote>
<p>to</p>
<blockquote>-command    ("if (`scriptedPanel -q -exists scriptEditorPanel1`) { scriptedPanel -e -to scriptEditorPanel1; showWindow scriptEditorPanel1Window; selectCurrentExecuterControl; <b>python(\"StdOut = syntax.wrap()\");</b> }else { CommandWindow; }")</blockquote>
<p>So our color syntax will be called everytime <b>scriptEditor;</b> is executed, whatever how the command is called =) !</p>
<dt id="32"></dt><h2>Finalizing</h2>
<p>Here is a bit more complex example to show you advanced use of the regular expressions</p>
<?php createCodeX("from PySide.QtGui import *
from PySide.QtCore import *
from shiboken import wrapInstance
from maya.OpenMayaUI import MQtUtil

class Rule():
    def __init__(self, fg_color, pattern='', bg_color=None, bold=False, italic=False):
        self.pattern = QRegExp(pattern)
        self.form = QTextCharFormat()
        self.form.setForeground(QColor(*fg_color))
        if bg_color:
            self.form.setBackground(QColor(*bg_color))
        font = QFont('Courier New', 9)
        font.setBold(bold)
        font.setItalic(italic)
        self.form.setFont(font)

class StdOut_Syntax(QSyntaxHighlighter):
    def __init__(self, parent, rules):
        super(StdOut_Syntax, self).__init__(parent)
        self.parent = parent
        self.rules = rules
    
    def highlightBlock(self, text):
        # applying each rules
        for rule in self.rules:
            pattern = rule.pattern        # regexp pattern
            index = pattern.indexIn(text)
            while index >= 0:
                # loop through the text to find matches
                len = pattern.matchedLength()   # length of the match
                self.setFormat(index, len, rule.form) # we apply the format to the match
                index = pattern.indexIn(text, index + len)
            self.setCurrentBlockState(0)
        
def wrap():
    i = 1
    while i:
        try:
            se_edit = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter%i' % i)),
                                                            QTextEdit)
            # we remove the old syntax and raise an exception to get out of the while
            assert se_edit.findChild(QSyntaxHighlighter).deleteLater()
        except TypeError:
            i += 1       # if we don't find the widget we increment
        except (AttributeError, AssertionError):
            break

    rules = [Rule((212, 160, 125), r'^//.+$', bold=True),
             Rule((185, 125, 255), r'^#.+$', italic=True),
             Rule((255, 175, 44), r'^(#|//).*(error|Error).+$')]

    StdOut = StdOut_Syntax(se_edit, rules)
    return StdOut
StdOut = wrap()", "Our final color syntaxer", true)?>
<p>A simple translation of the above regular expressions would be ;</p> 
<ul>
    <li style='color:#d4a07d;font-weight:bold;'>line starting with // until the end of the line</li>
    <li style='color:#9276ae;font-weight:bold;'>line starting with # until the end of the line</li>
    <li style='color:#ffaf49;font-weight:bold;'>line starting with # OR // and contains the word error or Error until the end of the line</li>
</ul>
<p>Here is the result ;</p>
<?php addImage("02.jpg", "Coloring the QTextEdit")?>
<p>You can test new rules in real time with the two following lines ; write your rules then copy them in your
<b>syntax.py</b> to have them everytime you launch Maya !</p>
<?php createCodeX("StdOut.rules.append(Rule((255,255,255), r'^.*?Result.*?$', bold=True))
StdOut.rehighlight()")?>
<p>Will add a rule to colorize each line which contains the word Result in white bold</p>
<p>Below the final file ;</p>
<?php 
    $_GET['n'] = 'syntax';
    $_GET['buttons'] = true;
    include_once './dl/wrap/index.php';
?>
<dt id="40"></dt><h1>Bonus</h1>
<p>Here is a little example of a more advanced use of PyQt, this will assign directly the Maya's Python color syntax  to the scriptEditor history, 
you'll just need to use what we've learned above to make it automatic ! I trust you =)</p> 
<?php createCodeX("from PySide.QtCore import *
from PySide.QtGui import *
from shiboken import wrapInstance as wrapinstance

from maya.OpenMayaUI import MQtUtil

se_repo = wrapinstance(long(MQtUtil.findControl('cmdScrollFieldReporter1')), QTextEdit)
tmp = cmds.cmdScrollFieldExecuter(sourceType='python')
se_edit = wrapinstance(long(MQtUtil.findControl(tmp)), QTextEdit)
se_edit.nativeParentWidget()
se_edit.setVisible(False)
high = se_edit.findChild(QSyntaxHighlighter)
high.setDocument(se_repo.document())")?>
<p>May the peace be with you</p>