<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
<title>Cr&eacute;ation d'un ik stretchable</title>

<BR><br>
<br>
<div align="center">
    <font class="title">
        <center>- STRETCH - <br>Creation d'un ik stretchable sur maya</center>
    </font>
    <br>
    <!--<br>
    <font class="description">
        
    </font>-->
    <br>
    <br>
</div>
<br>
<hr>
<br>
<br>
<font class='title'>
    Sommaire
</font>

<a href="<?php $actual_link;?>#10">
    <h1 class='sum' id='s0'>
        Intro
    </h1>
</a>
<a href="<?php $actual_link;?>#11">
    <h2 class='sum' id='s1'>
        Cr&eacute;ation de la scene
    </h2>
</a>
<a href="<?php $actual_link;?>#12">
    <h2 class='sum' id='s2'>
        Principe
    </h2>
</a>
<a href="<?php $actual_link;?>#20">
    <h1 class='sum' id='s3'>
        Premi&egrave;re &eacute;tape : stretch basique
    </h1>
</a>
<a href="<?php $actual_link;?>#21">
    <h2 class='sum' id='s4'>
        Distance dynamique
    </h2>
</a>
<a href="<?php $actual_link;?>#22">
    <h2 class='sum' id='s5'>
        Distance fixe
    </h2>
</a>
<a href="<?php $actual_link;?>#23">
    <h2 class='sum' id='s6'>
        Ratio
    </h2>
</a>
<a href="<?php $actual_link;?>#24">
    <h2 class='sum' id='s7'>
        Condition
    </h2>
</a>
<a href="<?php $actual_link;?>#25">
    <h2 class='sum' id='s8'>
        Conclusion
    </h2>
</a>
<a href="<?php $actual_link;?>#30">
    <h1 class='sum' id='s0'>
        Seconde &eacute;tape : attributs suppl&eacute;mentaires
    </h1>
</a>
<a href="<?php $actual_link;?>#31">
    <h2 class='sum' id='s1'>
        Activer / d&eacute;sactiver le stretch
    </h2>
</a>
<a href="<?php $actual_link;?>#32">
    <h2 class='sum' id='s2'>
        Activer / d&eacute;sactiver le squash
    </h2>
</a>
<a href="<?php $actual_link;?>#33">
    <h2 class='sum' id='s3'>
        Manual stretch
    </h2>
</a>
<a href="<?php $actual_link;?>#34">
    <h2 class='sum' id='s4'>
        Clamp
    </h2>
</a>
<a href="<?php $actual_link;?>#35">
    <h2 class='sum' id='s4'>
        Mid position
    </h2>
</a>
<a href="<?php $actual_link;?>#36">
    <h2 class='sum' id='s4'>
        Snap vers le pole vector
    </h2>
</a>
<a href="<?php $actual_link;?>#40">
    <h1 class='sum' id='s4'>
        Conclusion
    </h1>
</a>

<br>
    
    
    
    
<dt id="10"></dt>
<h1>
    Intro
</h1>
<P>
Dans ce tutorial nous allons voir de A a Z une methode pour creer un ik stretchable complet. Je me permets toutefois de bien insister sur le fait qu'il y a probablement autant de manieres de faire un stretch qu'il existe de riggers. Libre a vous de creer votre propre methode, une fois que vous aurez compris les avantages et inconvenients de chaque methode (et j'irai meme jusqu'a dire que c'est conseill&eacute;) ! Mais rentrons sans plus tarder dans le vif du sujet.
</P>

<dt id="11"></dt><h2>
    Cr&eacute;ation de la sc&egrave;ne
</h2>

<P>Pour ce tutorial, vous aurez besoin d'une chaine de 4 joints. Le premier sera 'shoulder, le second sera elbow, le troisieme wrist et le dernier wristEnd. Vous allez egalement avoir besoin de deux IKs, un ikRotatePlane du shoulder au wrist, et un ikSingleChain du wrist au wristEnd. Creez aussi un controller, que nous appelerons cc_hand (appliquez-lui un freezeWithgroup) ainsi qu'un poleVector (pv), effectif sur l'ikRP, et auquel vous aurez applique egalement un freezeWithGroup, evidemment.
Vous pouvez, pour finir, rajouter de la mod qui sera influencee par les joints, juste pour avoir une vision en temps reel des modifications que vous faites.</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/00.jpg" NAME="stretchyIk00" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<dt id="12"></dt><h2>
    Principe
</h2>


<P>Le principe du stretch (ou du moins du stretch que nous allons voir ici) est tr&egrave;s simple : nous allons chercher &agrave; obtenir une valeur qui sera &eacute;gale &agrave; 1 dans la position par d&eacute;faut, et qui grandira proportionnellement au fur et &agrave; mesure qu'on tend le bras. De cette mani&egrave;re, il nous suffira de brancher cette valeur dans le scale de nos joints (par d&eacute;faut a 1) pour obtenir l'effet de stretch. Sachez que certaines personnes (que nous appellerons "les autres" ;) font aussi leur stretch sur les valeurs de translate. A ma connaissance, il n'y a pas une m&eacute;thode plus efficace que l'autre, et pour avoir test&eacute; les deux, ma pr&eacute;f&eacute;rence va au scale, que je trouve plus simple a appr&eacute;hender. Mais c'est purement une question de go&eacute;ts (le strech par scale marchera moins bien si votre joint influence des vertex qui sont derriere lui, mais le translate ne produira pas une deform progressiveÉ), donc n'h&eacute;sitez pas a chercher d'autres tutos si vous pr&eacute;f&eacute;rez faire un stretch en translate (on en fera peut-&egrave;tre un sur 3dBunk si le temps le permet =). </P>

<P>Quoiqu'il en soit, nous allons donc commencer par mettre en place cette premiere &eacute;tape (r&eacute;cup&eacute;rer une valeur qui soit &eacute;gale &agrave; 1 quand le bras est tendu et plus grande quand le cc_hand s'&eacute;loigne). Puis, nous nous attacherons &agrave; rajouter quelques fonctions utiles, comme l'activation ou non du stretch, un attribut de clamp (pour fixer un palier au stretch), etc etc. </P>

<P>Pour r&eacute;cup&eacute;rer la valeur dont nous parlions plus haut (que nous appellerons le ratio), nous allons tout simplement diviser la longueur 'dynamique' du bras (j'appelle ÇÊlongueur dynamiqueÊÈ la distance qui s&eacute;pare le shoulder du cc_hand, et qui se mettra donc a jour en temps-reel quand on d&eacute;placera notre cc_hand) par la valeur fixe (la distance qui s&eacute;pare le shoulder du elbow + la distance qui s&eacute;pare le elbow du wrist, par d&eacute;faut, sans toucher aux joints.) C'est parti !</P>

<dt id="20"></dt><h1>
    Premi&egrave;re &eacute;tape : stretch basique
</h1>

<dt id="21"></dt><h2>
    Distance dynamique
</h2>

<P>Premi&egrave;rement, nous allons avoir besoin d'un &eacute;l&eacute;ment pour mesurer en temps r&eacute;el la distance du bras lorsqu'il est tendu. Cr&eacute;ez donc d&eacute;j&agrave; 2 locators. Le premier (loc_distRoot) sera plac&eacute; sur l'&eacute;paule et le second (loc_distTop) sera plac&eacute; sur le (et parent&eacute; au) cc_hand. Faites &eacute;videmment bien attention &agrave; ne pas le parenter au wrist lui-m&egrave;me, qui subira le scale, sans quoi vous cr&eacute;erez une double deform / cycle (le wrist d&eacute;placera le locator, qui cr&eacute;era un d&eacute;placement du wrist, et ainsi de suiteÉ).</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/01.jpg" NAME="stretchyIk01" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Graphez maintenant ces deux locators dans votre node editor (nous nous int&eacute;ressons ici a la shape et non au transform), puis cr&eacute;ez un node 'distanceBetween' (db_dynDist). Connectez ensuite le loc_distRootShape.worldPosition au point1 de votre db_dynDist, et le loc_distTopShape.worldPosition au point2 de votre db_dynDist. Sans surprise, votre distanceBetween vous renvoie maintenant, via son attribut 'distance', la distance en temps r&eacute;el.</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/02.jpg" NAME="stretchyIk02" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<dt id="22"></dt><h2>
    Distance fixe
</h2>

<P>Deuxi&egrave;mement, nous allons avoir besoin de la distance actuelle du bras, lorsqu'il est tendu (donc la distance shoulder/elbow additionn&eacute;e a la distance elbow/wrist, puisque notre bras est ici l&eacute;g&egrave;rement pli&eacute;)
Deux m&eacute;thodes :
<OL>
<LI>Cr&eacute;ez un distance tool qui va du shouder au elbow, puis un autre qui va du elbow au wrist. R&eacute;cup&eacute;rez ensuite la distance exacte de chaque mesure tool puis additionnez la :
<?php createCodeX("firstPart = cmds.getAttr('distanceDimensionShape1.distance')
print 'firstPart ==> ', firstPart
secPart = cmds.getAttr('distanceDimensionShape2.distance')
print 'secondPart ==> ', secondPart
print 'first part added to second part : '
print firstPart+secPart
");?>

en rempla&egrave;ant bien &eacute;videmment 'distanceDimensionShape1' et 'distanceDimensionShape2' par le nom de vos distance tool si vous les avez renomm&eacute;s.</LI>
<LI>Pour ceux qui veulent auto-rigger tout &egrave;a, sachez que vous pouvez r&eacute;cup&eacute;rer tr&egrave;s facilement la distance entre deux points si vous connaissez leur vecteur, avec un peu de trigonom&eacute;trie.
(indice : ca commence par :
<?php createCodeX("
cmds.xform(point1, q=True, ws=True, t=True)
cmds.xform(point2, q=True, ws=True, t=True)");?>
et ca se finit par un tour sur la page 'formules utiles' pour savoir comment, &agrave; partir de ca, r&eacute;cup&eacute;rer la longueur qui s&eacute;pare ces deux points =)</LI>
</OL></P>

<P>Chez moi, &egrave;a donne 7.28538328579. Vous pouvez supprimer les distanceTool, mais gardez bien cette valeur sous la main pour l'&eacute;tape suivante, et gardez &eacute;galement les deux valeurs s&eacute;par&eacute;es (la distance de shoulder &agrave; elbow et la distance de elbow &agrave; wrist), qui nous servirons encore plus tard !
</P>

<dt id="23"></dt><h2>
    Ratio
</h2>

<P>Maintenant que vous avez vos deux distances, il vous suffit de cr&eacute;er un node multiplyDivide (md_ratio). En input1X, connectez la distance dynamique issue du db_dynDist, et en input2X, rentrez manuellement la distance fixe. Et settez votre md_ratio sur l'op&eacute;ration ÇÊdivideÊÈ. 
</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/03.jpg" NAME="stretchyIk03" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Si vous avez suivi jusque l&agrave;, vous devriez avoir compris ce que nous venons de faire : dans l'&eacute;tat actuel des choses, la distance fixe &eacute;tant sup&eacute;rieure &agrave; la distance dynamique (puisque notre bras est l&eacute;g&egrave;rement pli&eacute;), le r&eacute;sultat du md_ratio sera inf&eacute;rieur a 1. Mais si le bras est parfaitement tendu, alors le md_ratio renverra 1. Et plus nous tirerons sur le bras, plus le md_ratio renverra un nombre sup&eacute;rieur a 1, puisque le num&eacute;rateur augmentera, mais pas le d&eacute;nominateur..
Vous aurez donc compris que si nous branchions maintenant le r&eacute;sultat de notre multiplyDivide, les joints seraient r&eacute;tr&eacute;cis de sorte a ce que le bras soit tout pile tendu. Nous allons donc avoir besoin d'un noeud de condition pour pr&eacute;ciser &agrave; maya qu'il ne doit appliquer &egrave;a QUE si le ratio est sup&eacute;rieur ou &eacute;gal &agrave; 1 (i.e. si le bras est tendu sans &egrave;tre r&eacute;tr&eacute;ci).
</P>

<dt id="24"></dt><h2>
    Condition
</h2>

<P>Ce que nous voulons faire, c'est 'filtrer' le r&eacute;sultat du multiplyDivide : s'il d&eacute;passe les 1 (et donc que nous avons besoin du stretch, puisque la distance dynamique sera plus grande que la distance fixe), alors on laisse passer la valeur. Si c'est l'inverse (et donc que le bras est pli&eacute;), alors on renvoie 1, la valeur de scale par d&eacute;faut. 
Cr&eacute;ez donc un noeud de condition (if_filter) et  connectez le outputX du multiplyDivide au firstTerm, ainsi qu'au colorIfTrueR. Dans le secondTerm, rentrez la valeur 1, et dans l'operation, indiquez Greater Than.
De cette mani&egrave;re, si le premier terme (donc notre ratio) est inf&eacute;rieur a 1, la condition renverra colorIfFalse (puisque notre condition sera fausse), a savoir 1. Ë l'inverse, si le firstTerm est plus grand que 1, notre condition renverra colorIfTrue, &agrave; savoir le ratio =)
Vous pouvez d&egrave;s &agrave; pr&eacute;sent connecter le outColorR de votre condition &agrave; votre shoulder et votre elbow.</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/04.jpg" NAME="stretchyIk04" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<dt id="25"></dt><h2>
    Conclusion
</h2>

<P>Voil&agrave;, vous avez maintenant un stretch =) Il est cependant ultra-basique, vous en conviendrez. Nous allons donc, dans les parties suivantes, s'employer &agrave; rajouter un max de fonctions pour avoir un stretch de l'espace !
Sans plus tarder, je vous propose de cr&eacute;er les attributs suivants sur votre cc_hand :
<UL>
<LI>stretch : enum(off:on) (je mets le off en premier parce qu'il renvoie 0 par d&eacute;faut, ca &eacute;vite un noeud de reverse =) Ceci dit, pour ceux qui scriptent, sachez que vous pouvez attribuer une valeur diff&eacute;rente a votre enum (du genre 'on' retournera 2 et 'off' retournera 45, par exemple). Enfin, si le contenu de cette parenth&egrave;se ne vous parle absolument pas, ce n'est pas du tout un probl&egrave;me pour la suite^^)</LI>
<LI>squash : enum(off:on) (je mets a nouveau le off en premier, pour les memes raisons que sur le stretch)</LI>
<LI>clamp : minimum a 1, pas de max, default a 1.5</LI>
<LI>manual stretch : float, minimum a -0.99, pas de max, default a 0</LI>
<LI>midPos : float, minimum a -0.75, maximum a 0.75, default a 0</LI>
</UL></P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/05.jpg" NAME="stretchyIk05" ALIGN=CENTER BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<dt id="30"></dt><h1>
    Seconde &eacute;tape : attributs suppl&eacute;mentaires
</h1>

<dt id="31"></dt><h2>
    Activer / d&eacute;sactiver le stretch
</h2>

<P>Rendez vous donc dans votre node editor. Nous allons avoir besoin du m&egrave;me arbre que pr&eacute;c&eacute;demment, &agrave; ceci pr&egrave;s que nous allons y rajouter notre cc_hand. En outre, je vous invite a cr&eacute;er un noeud d'unitConversion juste avant les joints. L'id&eacute;e est de faire vos branchements sur un seul noeud (le unitConversion) plut™t que sur tous vos joints &agrave; chaque fois. Nous utiliserons le unitConversion un peu &agrave; la mani&egrave;re d'un no-op, pour ceux qui connaissent nuke. Bien s&eacute;r, pour connecter &agrave; deux joints, ca reste faisable &agrave; la main, mais si vous avez un tentacule ou quelque chose avec beaucoup plus de joints, ce sera plus appr&eacute;ciable de faire votre RnD de cette mani&egrave;re (bon, ok, c'&eacute;tait surtout un pr&eacute;texte pour montrer l'astuce =)</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/06.jpg" NAME="stretchyIk06" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Pour cet attribut d'activation/d&eacute;sactivation du stretch, rien de bien compliqu&eacute;, ca va encore se passer avec des conditions. Comme d'habitude, prenons le temps d'&eacute;noncer en langage fran&egrave;ais ce que l'on veut faire. On veut ici '&eacute;couter' la valeur de l'attribut 'stretch'. Si elle est &eacute;gale a 0 ( = attribut sur 'off'), alors le stretch est inactif et donc on veut r&eacute;cup&eacute;rer la valeur '1' pour notre scale. A l'inverse, si elle est &eacute;gale a 1, alors le stretch est actif, et on veut r&eacute;cup&eacute;rer le ratio pour notre scale.
</P>

<P>Cr&eacute;ez donc un noeud de condition (if_switch). Dans le firstTerm, branchez cc_hand.stretch, et param&eacute;trez votre secondTerm sur 1. D&eacute;finissez ensuite l'operation comme &eacute;tant 'equal'. Enfin, branchez le result du md_ratio.outputX en colorIfTrueR du if_switch, et assurez-vous que colorIfFalseR est bien a 1 (sa valeur par d&eacute;faut). Si firstTerm == secondTerm (i.e. si le stretch est actif), alors on 'laisse passer' le ratio, sinon, on renvoie 1. Bien s&eacute;r, connectez ensuite le if_switch.outColorR a votre if_filter.colorIfTrueR. (l&agrave; o&egrave; &eacute;tait branch&eacute; le md_ratio)
</P>
<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/07.jpg" NAME="stretchyIk07" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<dt id="32"></dt><h2>
    Activer / d&eacute;sactiver le squash
</h2>

<P>Avant tout chose, la formule commun&eacute;ment admise - et que nous utiliserons ici - de conservation du volume, du moins en 3D, est 1 / racine carr&eacute;e du ratio. Gardez ca en t&egrave;te (vous pouvez le retrouver sur la page 'formules').
Maintenant, appliqu&eacute; &agrave; notre cas, voyons ce que ca donne. Le outColor qui sort de if_filter est la bonne valeur, notre ratio qui sera toujours bon, celui que l'animateur voudra. Il nous suffit donc de deriver cette valeur pour y appliquer la formule vue ci-dessus, qu'on re-injectera ensuite en scaleY et Z (si vous orientez vos joints en X).
Allons-y, donc ! Pour commencer, il nous faut appliquer 'racine carr&eacute;e'. Prenez donc le if_filter.outColorR, et branchez-le en input1X d'un multiplyDivide (md_squashSqrt). Param&eacute;trez le type d'operation sur 'pow' et le input2X sur 0.5. Appliquer une puissance (power en anglais) de 0.5 revient &agrave; faire l'op&eacute;ration 'racine carr&eacute;e'.
Connectez ensuite le outputX en input2X d'un nouveau multiplyDivide (md_squashInv), puis param&eacute;trez le type d'op&eacute;ration sur 'divide' et le input1X sur '1'. Ce noeud vous renverra donc l'inverse de ce que vous lui donnez. Enfin, connectez cet outputX aux scales Y et Z de vos joints (ou &agrave; un autre unitConvertion tampon si vous &egrave;tes feignant =)
</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/08.jpg" NAME="stretchyIk08" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Voila, votre squash est fait. Mais vous me direz que l'interrupteur que nous avons mis sur le controller est pour l'instant d'une utilit&eacute; r&eacute;duite. Effectivement, il n'est connect&eacute; &agrave; rien. Vous aurez aussi remarqu&eacute; que les noeuds de multiplyDivide, en plus de proposer comme op&eacute;ration ÇÊmultiplyÊÈ, ÇÊdivideÊÈ ou ÇÊpowerÊÈ, ont aussi une option ÇÊno operationÊÈ . Si on se r&eacute;f&egrave;re a la doc de maya, on trouve ceci :</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/09.jpg" NAME="stretchyIk09" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>C'est pr&eacute;cis&eacute;ment ce qu'on veut ! Je m'explique : on veut que nos deux multiplyDivide fonctionnent lorsque le squash est activ&eacute;, et qu'ils renvoient simplement ÇÊ1ÊÈ lorsque le squash est d&eacute;sactiv&eacute;. Il nous suffit donc de connecter notre attribut squash &agrave; md_squashInv (pr&eacute;cis&eacute;ment celui qui a comme valeur la constante 1 en input1X =). Quand le squash sera a 0 (off), md_squashInv.operation sera &eacute;galement a 0, ce qui correspond a 'no operation'. md_squashInv retournera donc son input1, a savoir 1.
Quand le squash sera &eacute;gal &agrave; 1 (on), l'operation du multiplyDivide sera Multiply. Pour ceux qui ne scriptent pas, sachez qu'on commence a compter a partir de 0 en script. Par consequent, si je fais la liste des op&eacute;rations du multiplyDivide ainsi que leur index, ca donne ca :
<UL>
    <LI>no operation ==> 0</LI>
    <LI>multiply ==> 1</LI>
    <LI>divide ==> 2</LI>
    <LI>power ==> 3</LI>
</UL>

On veut que le squash retourne 0 quand il est sur off (no operation), mais 2 quand il est sur on (divide). Il nous suffit donc de multiplier le squash par 2 ! De cette mani&egrave;re, sur on, il retournera bien 1, mais sera multipli&eacute; par 2 sur le chemin avant d'arriver au md_squashInv.input, et sera donc &eacute;gal a 2 ! Le 0, lui, ne changera pas (0x2 reste 0 ). Pour multiplier cette valeur, plut™t que de re-utiliser un multiplyDivide avec toutes ses options qui ne nous int&eacute;resserons pas ici, nous allons cr&eacute;er un unitConversion (uc_convertSquashOp). Le unitConversion agit comme un multiplyDivide &agrave; une entr&eacute;e et une op&eacute;ration (multiply).Bien que ce ne soit pas son r™le premier, je l'utilise souvent de cette mani&egrave;re parce qu'il me parait plus l&eacute;ger que le multiplyDivide. Branchez-lui le squash en input, param&eacute;trez le conversion factor sur 2, et branchez l'output sur le second multiplyDivide.operation. Et voil&agrave;, quand votre squash est &agrave; 0, le multiplyDivide est en 'no operation', et quand votre squash est &agrave; 1, le multiplyDivide est en 'divide' !
</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/10.jpg" NAME="stretchyIk10" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Enfin, pour ceux d'entre vous qui sont familiaris&eacute;s avec le python, sachez que vous pouvez aussi cr&eacute;er un attribut enum qui renverra ce que vous voulez (au lieu de 0 ou 1). Par exemple, si j'ex&eacute;cute :
cmds.addAttr('cc_hand', ln='squash', at='enum', enumName='off=0:on=2', k=True)
notre attr squash renverra - par d&eacute;faut - 2 quand il sera sur on ! Et ca, ca nous &eacute;vite d'utiliser le noeud de unitConversion, et c'est bien =)
</P>

<dt id="33"></dt><h2>
    Manual stretch
</h2>

<P>Ce que nous voulons faire avec le manual extend, c'est tout simplement animer manuellement le scale de nos joints de sorte &agrave; 'cr&eacute;er' du stretch sans bouger le cc_hand. L'attribut manual stretch va donc driver notre scale, ni plus ni moins =). On pourrait donc d&egrave;s maintenant brancher cet attribut dans notre premiere condition. Le souci, c'est que pour le bien-&egrave;tre de notre animateur, nous avons d&eacute;fini la valeur 'manual stretch' a 0, mais on aimerait qu'elle soit a 1, puisqu'on veut que ce soit la valeur par d&eacute;faut pour driver nos scales. Nous allons donc ajouter un plusMinusAverage. L'astuce, pour rajouter une valeur constante sur un pma, consiste &agrave; cr&eacute;er un attribut (que j'appelle en general tr&egrave;s originalement 'offset'), puis &agrave; le connecter &agrave; notre input1D. Vous notez par ailleurs que cette technique n'est n&eacute;cessaire que pour l'input1D, les input2D et input3D proposant un bouton 'add item' pour rentrer des constantes.</P>

<P>Bref, cr&eacute;ez donc un plusMinusAverage (pm_manStretch) et cr&eacute;ez lui un attribut 'offset' que vous d&eacute;finirez &agrave; 1 (peu importe le type de valeur, tout ce qu'on veut c'est r&eacute;cup&eacute;rer la valeur 1 !). Puis branchez ledit attribut en input1D[0]. Branchez ensuite cc_hand.manualStretch en input1D[1].</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/11.jpg" NAME="stretchyIk11" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Voila, nous avons notre valeur '1'. Vous pouvez maintenant la connecter &agrave; votre premi&egrave;re condition (if_switch). Nous allons cependant utiliser le channel colorIfTrueG. En effet, on va faire 'transiter' cette valeur, mais on a aussi besoin de la filtrer en fonction de le la valeur de l'attribut stretch on/off. Branchez-donc votre pm_manStretch.output1D dans le colorIfTrueG de votre condition if_switch.
</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/12.jpg" NAME="stretchyIk12" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Gardez ensuite le if_switch.outColorR dans le if_filter.colorIfTrueR, mais branchez le if_switch.outColorG en colorIfFalseR et en secondTerm, et gardez le ratio en firstTerm. Conservez l'operation du if_filter a 'greater than'. </P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/13.jpg" NAME="stretchyIk13" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Traduction ; 
Si le ratio est sup&eacute;rieur ou &eacute;gal &agrave; la valeur du 'manual stretch', alors on utilise le ratio.
A l'inverse, si le manual stretch est sup&eacute;rieur au ratio, alors on utilise la valeur du manual stretch.
</P>

<dt id="34"></dt><h2>
    Clamp
</h2>

<P>Le principe du clamp est tr&egrave;s simple : nous ajoutons un noeud de clamp (cl_clampStretch) entre le ratio et la premiere condition, pour laisser l'animateur choisir la valeur maximale de son stretch. Prenez-donc votre md_ratio.outputX, et branchez-le dans cl_clampStretch.inputR. En maxR, branchez la valeur de l'attribut clamp issue de cc_hand. Enfin, branchez le r&eacute;sultat du cl_clampStretch en colorIfTrueR de votre premi&egrave;re condition if_switch (on a donc juste rajout&eacute; le clamp entre le ratio et le if_switch).</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/14.jpg" NAME="stretchyIk14" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<dt id="35"></dt><h2>Mid Position</h2>
<P>L'attribut midPos va nous permettre de d&eacute;placer le point median (d&eacute;fini par vous) de votre bras. ç utiliser avec parcimonie cependant, en fonction de la longueur de vos elements, le r&eacute;sultat peut ne pas &egrave;tre optimal.
Nous voulons simplement appliquer un facteur multiplicateur sur la valeur du joint elbow (qui le fera grandir par exemple), et un autre facteur sur la valeur du joint shoulder (qui le fera r&eacute;tr&eacute;cir, sur le elbow grandit).
</P>

<P>Commencez par cr&eacute;er 2 plusMinusAverage (pm_midPosRoot et pm_midPosTop), avec chacun un attribut 'offset' a 1, que vous connecterez en input1D[0]. La difference entre ces deux plusMinus sera que le premier (root) sera en operation 'sum' tandis que le second (top) sera en operation 'substract'.</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/15.jpg" NAME="stretchyIk15" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Bien s&eacute;r, pensez &agrave; connecter l'attribut midPos de votre cc_hand en input1D[1] sur vos deux plusMinusAverage (donc pm_midPosRoot et pm_midPosTop).</P>

<P>Creez ensuite un multiplyDivide (md_midPos). Connectez-y votre pm_midPosRoot.output1D en input2X, et pm_midPosTop.output1D en input2Y (autant utiliser le meme multiplyDivide pour les deux que de cr&eacute;er un autre multiplyDivide =)
Puis, connectez le if_filter.outColorR dans le input1X et input1Y de votre multiplyDivid md_midPos
Voila, vous avez maintenant un facteur multiplicateur pour vos joints. Reste &agrave; connecter le outputX aux joints de la premi&egrave;re moiti&eacute; (pour nous, ce sera uniquement le shoulder) et le outputY au joint de la seconde moiti&eacute; (donc elbow, ici).
</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/15.jpg" NAME="stretchyIk15" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Voila, vous avez votre midPos, qui vous servira &agrave; faire varier (subtilement, bien sur ! Il ne s'agit pas de remod&eacute;liser le perso !) la position de l'articulation de votre perso !</P>

<dt id="36"></dt><h2>
    Snap vers le pole vector
</h2>


<P>Cr&eacute;ez enfin un attribut snapToPv sur votre pole vector, avec un minimum de 0, un max de 1, et une valeur par d&eacute;faut &agrave; 0 ! Le principe de cet attribut sera de permettre &agrave; l'animateur de snaper le coude au pole vector.
Pour ca, nous allons avoir besoin de calculer des ratios, une fois de plus =). Le scale actuel du shoulder et du elbow sont de 1. Il nous faut trouver de combien ils devraient &egrave;tre pour rejoindre le poleVector ! Pour commencer, cr&eacute;ez donc un locator sur le pv (et vous pouvez le parenter au pv dans la foul&eacute;e =). On appellera ce locator loc_distMid.</P>

<P>Commen&egrave;ons par le premier segment (de shoulder &agrave; elbow) :
<OL>
    <LI>Re-prenez la longueur que vous avez mesur&eacute; plus t™t (de shoulder &agrave; elbow), ou, si vous ne l'avez plus, re-mesurez la (appelons-la la valeur 1)</LI>
    <LI>Cr&eacute;ez un noeud de distanceBetween (db_rootToMid), et connectez-y loc_distRoot et loc_distMid. Le distanceBetween vous renvoie une longueur (que nous appellerons la valeur 2)</LI>
    <LI>Cr&eacute;ez un multiplyDivide (md_snapTo), et entrez-lui la valeur 1 en input1X, et la valeur2 en input2X. D&eacute;finissez l'op&eacute;ration comme &eacute;tant 'divide'.</P></LI>
</OL>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/16.jpg" NAME="stretchyIk16" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>Notre scene de base
        </font>
    </div>
    <BR CLEAR=LEFT></P>

<P>Voila, nous avons maintenant un ratio qui nous permet, si on le branche directement au joint shoulder, de modifier son &eacute;chelle de sorte &agrave; ce qu'il rejoigne le poleVector. Mais ne branchez rien pour l'instant, patience !</P>

<P>Proc&eacute;dez &agrave; la m&egrave;me op&eacute;ration pour le segment elbow-wrist :
<OL>
    <LI>Re-prenez la distance elbow-wrist que vous avez obtenu plus haut</LI>
    <LI>Cr&eacute;ez un noeud de distanceBetween entre loc_distMid et loc_distTop</LI>
    <LI>Connectez les deux valeurs obtenues ci-dessus &agrave; md_snapTo (inutile de re-cr&eacute;er un multiplyDivide !), en input1Y et input2Y.</LI>
</OL>
</P>

<P>La partie la plus 'tricky' maintenant consiste &agrave; int&eacute;grer tout &egrave;a &agrave; notre syst&egrave;me pr&eacute;c&eacute;dent ! Sur le principe, on peut consid&eacute;rer que cette option override toutes les autres (&agrave; l'&eacute;vidence, on ne veut pas snaper le bras au pv quand le perso a le bras tendu. Ou, si on le fait, alors &egrave;a pliera le bras).
Il nous suffit donc tout simplement de rajouter un blendTwoAttribute pour mixer les deux valeurs (la valeur qu'on a calcul&eacute; jusqu'&agrave; maintenant, qui sort de md_midPos, et la valeur du md_snapTo), mix&eacute; en fonction de l'attribut snapToPv de votre poleVector !
</P>

<P>Commencez-donc par cr&eacute;er un blendTwoAttr (ba_root), puis connectez l'outputX de votre md_midPos en input[0] de votre ba_root (c'est la valeur qui sera sortie du blendTwoAttr quand l'attributeBlender sera &agrave; 0). En input[1], connectez le md_snapTo.outputX. Et bien s&eacute;r, en guise d'attribute blender, connectez l'attribut snapToPv de votre pv.
Faites de meme pour la seconde moiti&eacute;. Cr&eacute;ez d'abord le blendTwoAttr (ba_top), et connectez y le md_midPos.outputY en input[0], le md_snapTo.outputX en input[1], et en blender, pv.snapToPv.
</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/stretchy_ik/img/17.jpg" NAME="stretchyIk17" ALIGN=CENTER BORDER=0>
	<font class='alt'>
        </font>
	<br>Notre scene de base
    </div>
    <BR CLEAR=LEFT></P>

<P>Vous avez maintenant votre attribut snapToPv fonctionnel, et qui sera mis &agrave; jour au mouvement de votre pole vector, puisque vous avez parent&eacute; le locator qui sert de mesure pour le ratio (loc_distMid).
</P>


<dt id="40"></dt><h1>Conclusion</h1>

<P>Voila, vous avez maintenant un stretch un peu plus riche que le stretch de base ! Il vous reste bien s&eacute;r ensuite &agrave; connecter tout &egrave;a au reste de votre perso et &agrave; ranger un peu votre outliner ! Vous pouvez trouver en telechargement sur le site un script fait tout ca automatiquement, si ca vous interesse, ainsi que la scene utilisee en exemple.
</P>
































<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
