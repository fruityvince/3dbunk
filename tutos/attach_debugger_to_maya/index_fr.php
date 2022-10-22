<?php include_once 't/head.php';?>

<dt id='10'></dt><h1>Concept</h1>
<P>L'idee derriere le process de debugging est de fournir un moyen de comprendre ce qui pose probleme lorsque maya freeze, passe trop de temps sur une operation en particulier, est bloque dans une loop infinie, etc... Donc en gros, ca devient tres utile dans les situations ou vous ne pouvez plus interagir avec maya.</P>

<P>Une ou deux precisions, avant d'entrer dans le vif du sujet : il s'agit d'une technique relativement avancee, a ne pas mettre necessairement entre toutes les mains, et certainement pas pour un usage quotidien. En outre, j'utilise ici un debugger qui s'appelle GDB. Je crois qu'il en existe d'autres, mais je ne les connais pas. Enfin, j'utilise pour ma part gdb sur Linux, ainsi que sur Mac OS (sur ma distribution de linux, gdb est natif, et sur mac os, un petit tour par homebrew vous permettra de l'installer tres facilement), mais je n'ai aucune idee de comment le faire fonctionner sur Windows.</P>

<dt id='11'></dt><h1>Recuperer le pID de maya</h1>

<P>Dans une fenetre de terminal, lancer la commande 'top'. Cette commande devrait vous afficher tous les programmes actuellement en cours d'execution. Ce que vous voulez trouver ici est le pID attache a l'instance de maya que vous souhaitez debugger. Une fois que vous avez votre pID, appuyez simplement sur la touche 'q' pour quitter.</P>

<?php addImage("01.jpg", "Et voila la liste de tous nos pIDs");?>

<dt id='12'></dt><h1>Lancer le debugger</h1>

<P>Toujours dans le terminal, lancez la commande :

<?php createCodeX("gdb -p numId");?>

en remplacant 'numID' par l'ID de votre maya, que nous venons de recuperer a l'etape precedente.</P>

<?php addImage("02.jpeg", "");?>

<P>A partir de la, le terminal vous retournera une serie de lignes incomprehensibles. Ces lignes representent tout ce que maya a fait depuis le debut de votre session (le debut etant la partie du bas, et les operations les plus recentes etant dans la partie superieure)</P>

<dt id='13'></dt><h1>Comprendre le debugger</h1>

<P>Pour rendre tout ca plus lisible, vous pouvez lancer (toujours dans le debugger) la commande backtrace (ou 'bt').</P>

<?php addImage("03.jpg", "Essayons de recuperer plus d'infos avec backtrace !");?>

<P>Backtrace va afficher dans le terminal tout ce qui a ete fait depuis le debut de votre session, dans une syntaxe beaucoup moins hostile ! Ce sera probablement plus lisible pour les gens familiarises avec l'API de maya, mais de maniere generale, tout ca fait sens, pour peu qu'on prenne le temps de lire ce qui est ecrit (et coup de chance, maya etant freeze, on l'a, le temps !). Par exemple, je suis sur que tout le monde comprend ce que TdependNode::getPlugValue peut faire =)</P>
<P>L'etape suivante dans notre phase de decodage de ce qui se passe dans maya consiste en l'utilisation de quelques librairies python externes, pour decompresser tout ca !</P>

<dt id='14'></dt><h1>Utiliser python pour decompresser les messages du debugger</h1>

<P>Il est necessaire d'utiliser une librairie externe, qui s'appelle libpython, pour proceder. Vous pouvez la trouver facilement en ligne (sur le repo svn python), ou vous pouvez recuperer celle qui est fournie ici, dans la section 'telechargements'.</P>

<P>Toutefois, pour que gdb ait connaissance de cette library, vous devez la declarer aupres de l'interpreteur python de gdb.</P>
<P>Pour ce faire, il vous suffit de lancer l'interpreter python dans gdb (en ecrivant simplement 'python' puis 'Entree'), et une fois dans l'interpreter python, ajouttez le chemin d'acces a votre library libpython pour cette session de python :</P>

<?php addImage("04.jpeg", "let's enhance our gdb python interpreter !");?>

<?php createCodeX("python
import sys
sys.path.insert(0, '/path/to/your/libpython/folder')
import libpython");?>

<P>Une fois fait, appuyez simplement sur ctl+d pour executer votre code et quitter.</P>

<P>Voila, vous etes prets a relancer un backtrace, a la difference que cette fois, vous aurez toutes les parties python decodees qui vous renverrons, a la ligne pres, a tout ce qui pourrait poser probleme.</P>

<P>Vous devez toutefois garder a l'esprit que des lors que vous attachez un debugger a une instance de maya, celle-ci sera 'bloquee', tant que votre debugger y sera attache. Vous pouvez voir ca comme 'je mets maya en pause le temps de l'ausculter'. Ne soyez donc pas surpris si une operation simple semble durer une eternite parce que vous avez un debugger attache a votre maya. Il ne se passera rien tant que le debugger sera la.</P>

