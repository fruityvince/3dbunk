<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>Introduction</h1>
<p>Ce tutorial va nous permettre d'aborder PyQt de mani&egrave;re extr&ecirc;mement succinte, et comment utiliser coupler sa puissance
&agrave; Maya, le probl&egrave;me &eacute;tant le suivant ; nous trouvons le retour des commandes Maya dans le 
script Editor trop fade, et souhaitons y ajouter quelques couleurs afin d'en faciliter la lisibilit&eacute;.</p>
<p>Nous allons tout d'abord t&acirc;cher d'identifier notre fen&ecirc;tre de scriptEditor, puis de r&eacute;cup&eacute;rer
l'objet du <b>QTextEdit</b> qui nous int&eacute;resse, puis nous y assignerons un <b>QSyntaxHighlighter</b>. Des notions
d'expressions r&eacute;guli&egrave;res peuvent &ecirc;tre utile pour cr&eacute;er ses propres colorations, let's start !</p>
<?php addTip("Ces deux sites sont vraiment d'une grande aide lorsqu'on veut pousser un peu plus loin les expressions r&eacute;guli&egrave;res !
		<br><a href='https://regex101.com/'>Online Regex Tester</a><br>
		<a href='https://en.wikipedia.org/wiki/Regular_expression'>L'article Wikipedia</a><br>");?>
		
<dt id="20"></dt><h1>PyQt</h1>
<dt id="21"></dt><h2>Identifier l'objectif</h2>
<p>Nous allons donc rechercher le widget PyQt qui nous int&eacute;resse gr&acirc;ce &agrave; la fonction 
<b><i>wrapinstance</i></b> de <b>sip</b>, aka <b><i>wrapInstance</i></b> pour <b>shiboken</b> ainsi que l'outil 
<b><i>MQtUtil</i></b> de <b>OpenMayaUI</b> afin de chercher dans Maya le ou les widgets recherch&eacute;(s), commen&ccedil;ons 
par trouver la fen&ecirc;tre principale de Maya ;</p>
<?php addNote("La fonction <b>wrapInstance</b> n&eacute;cessite un 'id' unique correspondant &agrave; l'identifiant du QWidget, ainsi que 
la classe principale de laquelle est issu l'object, en l'occurence nous recherchons la fen&ecirc;tre principale, la classe 
sera donc <b>QWidget</b>.");
?>
<br>
<?php 
createCodeX("from PySide import QtCore, QtGui
from shiboken import wrapInstance
from maya.OpenMayaUI import MQtUtil

maya_win = wrapInstance(long(MQtUtil.mainWindow()), QWidget)");?>
<p>Ceci &eacute;tant fait, nous pouvons maintenant it&eacute;rer &agrave; travers les enfants de la fen&ecirc;tre Maya afin de t&acirc;cher de trouver
notre bohneur, &agrave; savoir la fen&ecirc;tre du scriptEditor, diff&eacute;rentes approches peuvent &ecirc;tre abord&eacute;es, nous allons pour cet
exemple chercher les enfants contenant '<i>script</i>' dans leur nom d'objet, &agrave; fortiori la fen&ecirc;tre du scriptEditor
devrait matcher =) !</p>
<?php createCodeX("for child in maya_win.children():
    if 'script' in child.objectName():
        print 'CHILD => %s' % child.objectName()");
?>
<p>One shot ! Vous devriez avoir un retour de ce genre ;</p>
<?php createCodeX("# CHILD => scriptEditorPanel1Window");?>
<p>Nous avons maintenant le nom de la fen&ecirc;tre Maya et surtout, l'objet correspondant. La seconde d&eacute;marche sera d'it&eacute;rer
&agrave; travers les nombreux enfants de cette fen&ecirc;tre afin de trouver le <b><i>QTextEdit</i></b> du retour utilisateur. La 
m&eacute;thode est sensiblement la m&ecirc;me, bien qu'il faille parcourir r&eacute;cursivement tout les enfants de la fen&ecirc;tre pour en trouver
les instances de <b><i>QTextEdit</i></b>, r&eacute;cup&eacute;rant ainsi le nom correct, nous vous &eacute;pargnons la d&eacute;marche en d&eacute;voilant 
d&egrave;s &agrave; pr&eacute;sent le nom du <b><i>QTextEdit</i></b> ; j'ai nomm&eacute; <b>cmdScrollFieldReporter1</b>, prenez note que ce widget 
&agrave; un nom unique, et qu'un chiffre est rajout&eacute; automatiquement &agrave; la fin. Nous pouvons donc rencontrer <b>cmdScrollFieldReporter4</b>.</p>
<p>Gr&acirc;ce &agrave; la super classe <b>MQtUtil</b> nous avons d&eacute;sormais acc&egrave;s au widget nous int&eacute;ressant avec la fonction <b><i>findControl</i></b> ;</p>
<?php createCodeX("script_stdout = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter1')), QTextEdit)");?>
<p>&Eacute;tant donn&eacute; que nous ne connaissons pas l'index exact du nom de notre widget nous allons user d'une petite boucle
while afin de trouver la bonne occurence ;</p>
<?php createCodeX("i = 1
while i:
    try:
        se_edit = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter%i' % i)),
                                                        QtGui.QTextEdit)
        break 
    except TypeError:
        i += 1")?>
<p>Tr&egrave;s bien ! Maintenant que nous avons notre widget nous allons pouvoir travailler sur notre widget de coloration
syntaxique</p>
<dt id="22"></dt><h2>Le QSyntaxHighlighter</h2>
<p>L'application du <b><i>QSyntaxHighlighter</i></b> se fait tr&egrave;s simplement en lui enjoignant l'objet du parent auquel 
s'appliquer comme premier argument, comme ce qui suit ;</p>
<?php createCodeX("class StdOut_Syntax(QSyntaxHighlighter):
    def __init__(self, parent):
        super(StdOut_Syntax, self).__init__(parent)")?>
<p>PyQt utilise la fonction <b>highlightBlock</b> pour g&eacute;rer la coloration syntaxique, cette fonction n&eacute;cessite un texte en
input, dans lequel nous allons it&eacute;rer pour chaque expression r&eacute;guli&egrave;re que vous voulons colorer, pour d&eacute;finir un format pour 
cette expression avec la fonction <b>setFormat</b>, voici un simple exemple ;</p>
<?php createCodeX("class StdOut_Syntax(QSyntaxHighlighter):
    def __init__(self, parent):
        super(StdOut_Syntax, self).__init__(parent)
    
    def highlightBlock(self, text):
        color = QColor(255, 125, 160)
        pattern = QRegExp(r'^//.+$')        # regexp pattern
        keyword = QTextCharFormat()
        keyword.setForeground(color)         # on definit l'aspect du format
        index = pattern.indexIn(text)
        while index >= 0:
            # on iter dans le texte tant que le pattern trouve une occurence
            len = pattern.matchedLength()   # longueur de l'occurence
            self.setFormat(index, len, keyword) # on applique le format pour l'occurence
            index = pattern.indexIn(text, index + len)
        self.setCurrentBlockState(0)")?>
<?php addNote("L'expression r&eacute;guli&egrave;re utilis&eacute;e, on ne peut plus simple peut &ecirc;tre traduit de la fa&ccedil;on suivante ;<br>
<b>Si la ligne commence par '//' en prenant un maximum de caract&egrave;res jusqu'&agrave; la fin de ligne</b><br>
<b>^</b>  correspond au d&eacute;but de la ligne<br>
<b>.+</b> greedy expression pour prendre un maximum de caract&egrave;res, le '.' d&eacute;signant n'importe quel caract&egrave;re<br>
<b>$</b>  correspond &agrave; la fin de la ligne")?>
<p>En appliquant cette classe &agrave; notre widget <b>cmdScrollFieldReporter</b> avec le code suivant nous devrions obtenir ;</p>
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
        keyword.setForeground(color)         # on definit l'aspect du format
        index = pattern.indexIn(text)
        while index >= 0:
            # on iter dans le texte tant que le pattern trouve une occurence
            len = pattern.matchedLength() # longueur de l'occurence
            self.setFormat(index, len, keyword) # on applique le format pour l'occurence
            index = pattern.indexIn(text, index + len)
        self.setCurrentBlockState(0)
def wrap():
    i = 1
    while i:
        try:
            se_edit = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter%i' % i)),
                                                            QTextEdit)
            # on supprime l'ancienne syntaxe et raise une exception afin de sortir du while
            assert se_edit.findChild(QSyntaxHighlighter).deleteLater()
        except TypeError:
            i += 1       # si on ne trouve pas de widget on incremente
        except (AttributeError, AssertionError):
            break
    return StdOut_Syntax(se_edit)
wrap()")?>
<p>Ce qui fait que chaque fois qu'on rencontrera l'expression r&eacute;guli&egrave;re "<b>d'une ligne commen&ccedil;ant par //</b>" on la colorisera en rouge ! Ainsi ;</p>
<?php addImage("01.jpg", "Les pr&eacute;misses du succ&egrave;s =)")?>
<?php addNote("Un QTextEdit n'accepte qu'un QSyntaHighlighter associ&eacute;, si un second vient se rajouter le r&eacute;sultat risque d'&ecirc;tre 'unpredictable',
il vaut donc mieux supprimer pr&eacute;alablement tout QSyntaxHighlighter parmis les enfants de notre QTextEdit !")?>
<p>Passons donc &agrave; l'impl&eacute;mentation de notre syntaxe afin de la "lier" &agrave; Maya !</p>
<dt id="30"></dt><h1>Liaison intrins&egrave;que &agrave; Maya</h1>
<dt id="31"></dt><h2>Lancement au d&eacute;marrage</h2>
<p>L'ex&eacute;cution de scripts personnalis&eacute;s au d&eacute;marrage de Maya se fait par l'interm&eacute;diaire du fichier <b>userSetup.py</b> dans votre dossier Maya/scripts
dans vos documents, si ce fichier n'existe pas, cr&eacute;ez-le.</p>
<p>Nous allons copier notre super code pr&eacute;alablement &eacute;crit dans un nouveau fichier <b><i>syntax.py</i></b> dans ce dossier &agrave; la ra&ccedil;ine,
puis nous allons &eacute;diter notre fichier <b>userSetup.py</b> afin d'y rajouter la ligne suivante ;</p>
<?php createCodeX("import syntax")?><br>
<?php addTip("L'usage de la fonction <b>evalDeferred</b> de Maya est souvent recommand&eacute; afin de diff&eacute;rer l'execution de code
au moment o&ugrave; Maya est 'disponible'.<br><br>Notre cas &eacute;tant un simple import, nous n'en aurons pas l'usage =)")?>
<p>Tr&egrave;s bien, maintenant, en ouvrant notre scriptEditor, et en tapant </p><?php createCodeX("syntax.wrap()")?><p> notre <b>QTextEdit</b> de retour
de commande devrait prendre des couleurs !</p>
<dt id='32'></dt><h2>Un peu de piraterie !</h2>
<p>Maintenant que nous avons notre superbe fonction qui colorise le retour des commandes Maya, il va nous falloir le lier &agrave; Maya afin
qu'&agrave; chaque fois que le scriptEditor s'ouvre, le QSyntaxHighlighter se parente au QTextEdit voulu ! Car lors de la fermeture de la
fen&ecirc;tre, la liaison au scriptEditor disparait, le widget &eacute;tant supprim&eacute; et notre syntaxe aussi par la m&ecirc;me occasion, ce qui est 
malheureux vous en conviendrez.</p>
<p>Si nous activons l'option "<b>Echo All Commands</b>" du scriptEditor de Maya nous nous apercevons que la commande <b><i>scriptEditor;</i></b> est
appel&eacute;e &agrave; l'ouverture de la fen&ecirc;tre Apr&egrave;s une petite recherche dans <b>Window &rarr; Settings/Preferences &rarr; Hotkey Editor</b> dans la 
section <b>Window</b> on trouve que la fonction compl&egrave;te est ; </p>
<blockquote>if (`scriptedPanel -q -exists scriptEditorPanel1`) { scriptedPanel -e -to scriptEditorPanel1; showWindow scriptEditorPanel1Window; selectCurrentExecuterControl; }else { CommandWindow; }</blockquote>
<p>Malheureusement ces fonctions 'internes' &agrave; Maya ne sont pas modifiable, et m&ecirc;me si elles l'&eacute;taient, cela ne correspondrait qu'&agrave; l'execution 
de notre fen&ecirc;tre lors du raccourci clavier, mais pas lorsque nous appuyons sur le petit bouton <img src='t/scriptEditor_color/img/se.png'> par exemple,
il continuera d'executer la fonction susmentionn&eacute;e, et non pas la 'hotkey' modifi&eacute;e.</p>
<p>Nous allons donc nous balader dans les fichier internes &agrave; Maya avec l'espoir de trouver notre bonheur, usant d'un logiciel comme <?php dl("Notepad ++", "https://notepad-plus-plus.org/download")?>
nous allons pouvoir rechercher &agrave; travers tout les fichiers de Maya afin de trouver celui dans lequel notre commande est stock&eacute;e.</p>
<p>Une recherche rapide - et fructueuse =) - nous apprends que le fichier <b>defaultRunTimeCommands.mel</b> dans le dossier <b>scripts/startup</b> de Maya
contient ce que l'on cherche.<br>
Ce fichier est &eacute;norme, et la ligne recherch&eacute;e diff&egrave;re selon les versions de Maya, pour Maya 2013 la commande est &agrave; la ligne 4096, pour Maya 2014
la ligne 4228, chercher simplement <b>scriptEditor;</b> dans le fichier vous amenera &agrave; la bonne ligne =) !</p>
<p>Nous allons simplement rajouter un bout de commande MEL dans la commande de Maya en changeant </p>
<blockquote>-command    ("if (`scriptedPanel -q -exists scriptEditorPanel1`) { scriptedPanel -e -to scriptEditorPanel1; showWindow scriptEditorPanel1Window; selectCurrentExecuterControl; }else { CommandWindow; }")</blockquote>
<p>par</p>
<blockquote>-command    ("if (`scriptedPanel -q -exists scriptEditorPanel1`) { scriptedPanel -e -to scriptEditorPanel1; showWindow scriptEditorPanel1Window; selectCurrentExecuterControl; <b>python(\"StdOut = syntax.wrap()\");</b> }else { CommandWindow; }")</blockquote>
<p>Ce qui aura pour effet d'appliquer notre coloration syntaxique &agrave; chaque ouverture du scriptEditor, et ce quelle que soit la mani&egrave;re de l'ouvrir =) !</p>
<dt id="32"></dt><h2>Finalisation</h2>
<p>Voici pour finir un exemple un peu plus complexe d'usage des expressions r&eacute;guli&egrave;res.</p> 
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
        # on iter a travers nos regles
        for rule in self.rules:
            pattern = rule.pattern        # regexp pattern
            index = pattern.indexIn(text)
            while index >= 0:
                # on iter dans le texte tant que le pattern trouve une occurence
                len = pattern.matchedLength() # longueur de l'occurence
                self.setFormat(index, len, rule.form)
                index = pattern.indexIn(text, index + len)
            self.setCurrentBlockState(0)
        
def wrap():
    i = 1
    while i:
        try:
            se_edit = wrapInstance(long(MQtUtil.findControl('cmdScrollFieldReporter%i' % i)),
                                                            QTextEdit)
            # on supprime l'ancienne syntaxe et raise une exception afin de sortir du while
            assert se_edit.findChild(QSyntaxHighlighter).deleteLater()
        except TypeError:
            i += 1       # si on ne trouve pas de widget on incremente
        except (AttributeError, AssertionError):
            break
    rules = [Rule((212, 160, 125), r'^//.+$', bold=True),
             Rule((185, 125, 255), r'^#.+$', italic=True),
             Rule((255, 175, 44), r'^(#|//).*(error|Error).+$')]

    StdOut = StdOut_Syntax(se_edit, rules)
    return StdOut
StdOut = wrap()", "Notre coloration syntaxique finale", true)?>
<p>Une lecture simple des r&egrave;gles ajout&eacute;es ci-dessus serait ;</p> 
<ul>
    <li style='color:#d4a07d;font-weight:bold;'>ligne commen&ccedil;ant par // jusqu'&agrave; la fin de la ligne</li>
    <li style='color:#9276ae;font-weight:bold;'>ligne commen&ccedil;ant par # jusqu'&agrave; la fin de la ligne</li>
    <li style='color:#ffaf49;font-weight:bold;'>ligne commen&ccedil;ant par # OU // et contenant le mot error ou Error jusqu'&agrave; la fin de la ligne</li>
</ul>
<p>Et voil&agrave; le r&eacute;sultat ;</p>
<?php addImage("02.jpg", "Coloration syntaxique de notre QTextEdit")?>
<p>Vous pouvez tester de nouvelles r&egrave;gles en temps r&eacute;el avec les deux lignes de commande suivantes ; faites 
vos r&eacute;glages puis impl&eacute;mentez vos nouvelles r&egrave;gles dans votre fichier <b>syntax.py</b></p>
<?php createCodeX("StdOut.rules.append(Rule((255,255,255), r'^.*?Result.*?$', bold=True))
StdOut.rehighlight()")?>
<p>Rajoutera une r&egrave;gle de colorisation pour chaque ligne contenant le mot <i>Result</i> en gras.</p>
<p>Vous trouverez ci-apr&egrave;s le fichier final</p>
<?php 
    $_GET['n'] = 'syntax';
    $_GET['buttons'] = true;
    include_once './dl/wrap/index.php';
?>
<dt id="40"></dt><h1>Bonus</h1>
<p>Voil&agrave; un petit exemple d'un usage un peu plus avanc&eacute; en PyQt afin d'assigner directement la coloration syntaxique de Maya &agrave; l'historique
de commandes, utilisez ce que nous avons appris pr&eacute;c&eacute;demment pour mettre en place son execution de mani&egrave;re automatique ! Je vous fait confiance ;)</p>
<?php createCodeX("from PySide.QtCore import *
from PySide.QtGui import *
from shiboken import wrapInstance as wrapinstance

from maya.OpenMayaUI import MQtUtil

try:
    se_edit.deleteLater():
except:
    pass
		
se_repo = wrapinstance(long(MQtUtil.findControl('cmdScrollFieldReporter1')), QTextEdit)
		
tmp = cmds.cmdScrollFieldExecuter(sourceType='python')
se_edit = wrapinstance(long(MQtUtil.findControl(tmp)), QTextEdit)
se_edit.nativeParentWidget()
se_edit.setVisible(False)
		
high = se_edit.findChild(QSyntaxHighlighter)
high.setDocument(se_repo.document())")?>
<p>Que la paix soit avec vous</p>