<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
<br>
<br>
<div align="center">
    <font class="title">
        <center>- GAMMA ET LINEAR WORKFLOW - <br>Comprendre le gamma</center>
    </font>
    <br>
    <br>
    <font class="description">
        Explications de base sur les profils colorimétriques et le linear workflow
    </font>
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

<a href="t/intro_python/index.php#10">
    <h1 class='sum' id='s0'>
        Théorie
    </h1>
</a>
<a href="index.php#20">
    <h2 class='sum' id='s1'>
        Histoire
    </h2>
</a>
<a href="index.php#21">
    <h2 class='sum' id='s2'>
        Conséquences techniques
    </h2>
</a>
<a href="index.php#22">
    <h1 class='sum' id='s3'>
        Pratique
    </h1>
</a>

<dt id="10">
    
</dt>
<h1>
    Théorie
</h1>

<h2>Histoire</h2>

<P>Pour comprendre correctement le gamma, il faut revenir aux origines du mal ! Aux débuts de l’informatique et de l’analogique, quand on a commencé à vouloir afficher des couleurs sur des écrans, on s’est très vite aperçus que, si la majorité des couleurs pouvaient s’afficher correctement en terme de teinte, on avait des difficultés à respecter les intensités des noirs et des blancs (pour la petite histoire, ca vient de l’intensité du signal electrique en entrée, qui perd en precision quand elle est trop faible ou trop forte). Ainsi, si dans la réalité on avait une couleur avec 60% de noir, la meme couleur ressortait à l’écran beaucoup plus sombre, et de même pour les blancs. 
Voici un schéma très simplifié des noirs et blancs tels qu’on les perçoit dans le vrai monde réel de la réalité véritable.</P>

<P>
    <div align='center' class='content'>
        <img class='content' SRC="t/gamma_et_linearWorkflow/img/00.jpg" NAME="gamma01" ALIGN=CENTER WIDTH=640 BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>En ordonnée (axe vertical donc, pour rappel ;-), les noirs/blancs telles qu’elles sont, de manière théorique, et en abscisse, la manière dont nous les voyons (ce raisonnement est un peu abstrait et très théorique, évidemment, mais vous comprendrez par la suite !)
Jusqu’ici pas de problème, on voit la réalité. On perçoit notre environnement en termes de luminosité beaucoup plus qu’en terme de couleurs. Pour reprendre les modes colorimétriques les plus répandus, disons que l’impression et la peinture sont en CMYN, les écrans sont en RVB, et l’oeil humain est en TSL, donc basés sur la lumière.</P>

<P>Voyons maintenant une courbe indiquant toujours la réalité en ordonnée (donc la valeur TSL, en quelque sorte), mais cette fois, les abscisses correspondent à ce que renverra un moniteur, avec son RVB.</P>


<P><div align='center' class='content'><img class='content' SRC="t/gamma_et_linearWorkflow/img/01.jpg" NAME="gamma02" ALIGN=CENTER WIDTH=640 BORDER=0></div><BR CLEAR=LEFT></P>

<P>On observe qu’à une valeur ‘réelle’ de 0.9, l’écran nous renverra une valeur de 0.97, donc quelque chose de beaucoup plus sombre. Bien sûr, mon schéma est approximatif, la courbe de gamma n’est pas juste, je me suis surtout attaché au principe =)</P>

<P><div align='center' class='content'><img class='content' SRC="t/gamma_et_linearWorkflow/img/02.jpg" NAME="gamma03" ALIGN=CENTER WIDTH=640 BORDER=0></div><BR CLEAR=LEFT></P>

<P>Vu différemment, sur une plage de couleur allant de 0 a 10, 0 étant l’absence de couleur, en RVB, on aura la ligne du haut, tandis qu’en TSL, on aura la ligne du bas. La encore, je ne me suis pas attaché à la justesse de mes couleurs, mais au principe. En outre, si vous refaites le test avec votre photoshop, la manière de gérer les profils colorimétriques ainsi que les modes d’image vous obligerons à bidouiller un peu pour obtenir le meme résultat.</P>

<P>Le souci qui se présentait alors pour corriger ca était que la correction nécessaire était différente en fonction de la valeur de noir/blanc. Il n’était pas possible de simplement ajouter +2 de blanc partout, par exemple. Vous voyez sur la courbe qu’il faudrait -0.7 quand on est a 0.9, mais quand on est à 1, on est bons. De même sur l’échelle allant de 1 à 10, vous constatez que le noir et le blanc sont justes, c’est le milieu qui est plus approximatif !
La solution à ca, pour ceux qui se souviennent des cours de maths, c’est une fonction ! Et cette fonction particulière, on l’a appelée le gamma =).</P>


<h2>Conséquences techniques</h2>

<P>Revenons maintenant à nos écrans. On sait maintenant que tous les écrans cathodiques ont du se faire appliquer une fonction gamma correctrice, pour afficher toutes les images correctement. Concretement, l’écran rajoute une fonction inverse, qui ressemble grossièrement à ça :</P>

<P><div align='center' class='content'><img class='content' SRC="t/gamma_et_linearWorkflow/img/03.jpg" NAME="gamma04" ALIGN=CENTER WIDTH=640 BORDER=0></div><BR CLEAR=LEFT></P>

<P>De cette manière, l’image trop sombre était gamma-corrigée, de sorte à ressortir exacte (ou du moins, plus proche de la réalité =) En résumé, ça donne ça :</P>

<P><div align='center' class='content'><img class='content' SRC="t/gamma_et_linearWorkflow/img/04.jpg" NAME="gamma05" ALIGN=CENTER WIDTH=640 BORDER=0><BR CLEAR=LEFT>
<font class='alt'><br>courbe verte : réalité (TSL) / courbe bleue : couleurs ressorties par le moniteur, <br>par default / courbe rouge : correction appliquée à l’input pour se rapprocher d’une courbe linéaire en output</font></div><BR CLEAR=LEFT></P>

<P>Vous notez au passage que ce graphique est le meme que l’icône du noeud gammaCorrect de Maya ;-) </P>

<P>Bien sûr, maintenant, avec les écrans numériques et l’évolution de la technologie, on n’a plus ce problème d’intensité des noirs et blancs, et de devoir gamma-corriger les images. Le souci, c’est que la transition ne s’est pas faite du jour au lendemain, et on a donc hérité de cette fonction de gamma correction meme si on n’en a plus vraiment besoin (et en meme temps, ca aurait été pas mal le bordel si on avait eu des images g-corrigées, d’autres pas, plus moyen de savoir quoi corriger et quoi conserver). Tous nos écrans disposent donc de profils différents de correction colorimétrique. Le profil le plus répandu, que vous avez forcement deja croisé, c’est le sRGB, qui correspond à un gamma de 2.2 
Vous savez maintenant ce que qui se cache derriere ce fameux ’sRGB’ , et je vous invite même à consulter l’article wikipedia, qui vous donnera non seulement la signification du sigle sRGB (mais c’est un peu décevant =), mais surtout une explication finalement très claire du gamma (alors que la page wikipedia du gamma, justement, est beaucoup plus hermétique !)</P>

<h1>Pratique</h1>

<P>Tout ca est très intéressant, certes, mais vous allez me dire que ca n’a pas grand intérêt dans la 3D. Hé bien détrompez-vous ! Quand on fait un rendu, on a besoin de raytracing. Et si j’ai une image gamma-corrigée qui est utilisée dans le calcul, ca signifie que votre moteur de rendu va se baser sur une image déjà corrigée, et va re-corriger ensuite le résultat. Vous aurez donc toutes vos textures doublement gamma-corrigées ! L’intérêt de bosser dans un linear workfow est que vous n’altérerez pas vos textures travaillant avec des images "techniquement" fausses !</P>

<P>Concrètement, ca va beaucoup dépendre de votre moteur de rendu, de votre soft de rendu, et même de la production. Il y a 1000 manières différentes de travailler dans un espace colorimétrique linéaire, mais maintenant que vous avez les connaissances théoriques, l’essentiel est juste que vous compreniez ce que vous faites dans votre soft 3D, quel qu'il soit. Je vais ici m’attarder sur une méthode valable sous maya/mentalRay, et éviter de fournir une liste exhaustive de tous les paramètres qui influent sur le gamma, pour essayer de ne pas vous embrouiller outre mesure, mais libre à vous d’adapter votre workflow comme vous le souhaitez. Le tout étant d’éviter de supprimer toute correction au final, ou de tout g-corriger deux fois.
Si vous travaillez sur une version antérieure a maya 2011, vous devrez le faire a l’ancienne, c’est a dire rajouter un noeud de gamma correct a 0.45454545454545… (vous pouvez vous arrêter a 0.455^^) entre votre noeud de file et votre shader, de sorte a de-corriger votre image (l’inverse de 2.2, donc 1/2.2, c’est 0.454545…)</P>

<P>Maintenant, j’imagine que plus grand monde ne bosse avec une version antérieure a 2011, aussi nous ne nous attarderons pas sur cette méthode. À partir de maya2011, Autodesk a mis en place le color management, qui facilite grandement la vie, en plus d’être plus parlant (d’après moi) !
Tout ce que vous avez à faire, dans un premier temps, c’est de cocher, dans vos renderSettings, onglet Common, ‘enable color management’.
Ensuite, vous devez indiquer à maya quelles images vous lui entrez, en l’occurrence, des images gammaCorrigées (puisque tout ce qui vient d’internet, de votre appareil photo, de votre scanner, etc etc, est gamma-corrigé pour s’afficher ‘juste’ sur votre écran), donc default input profile => sRGB.
Et vous lui dites ensuite que vous voulez sortir du linearSRGB, pour dire a maya de ‘dé-corriger’ les images dans la scene de rendu.</P>

<P><div align='center' class='content'><img class='content' SRC="t/gamma_et_linearWorkflow/img/05.jpg" NAME="gamma06" ALIGN=CENTER WIDTH=440 BORDER=0></div><BR CLEAR=LEFT></P>

<P>Et là, le problème, c’est qu’on récupèrera, dans la renderView, des images toutes sombres, puisque PAS gamma corrigées, meme si techniquement plus justes ! Mais pas de panique ! Déjà, vous pouvez, dans la renderView, aller dans le menu display>colorManagement.</P>

<P><div align='center' class='content'><img class='content' SRC="t/gamma_et_linearWorkflow/img/06.jpg" NAME="gamma07" ALIGN=CENTER WIDTH=440 BORDER=0></div><BR CLEAR=LEFT></P>

<P>Ceci aura pour effet d’ouvrir une fenêtre dans l’attributeEditor</P>

<P><div align='center' class='content'><img class='content' SRC="t/gamma_et_linearWorkflow/img/07.jpg" NAME="gamma08" ALIGN=CENTER WIDTH=440 BORDER=0></div><BR CLEAR=LEFT></P>

<P>Et là, il suffit de dire à maya que ce qu’on envoie dans la renderView, c’est du linearSRGB (donc une image non-corrigée, comme on a vu plus haut), mais vous précisez en dessous d’afficher ça en sRGB, pour pouvoir malgré tout travailler votre lighting avec les bonnes couleurs !</P>

<P>Ensuite, pour un peu que vous utilisiez un logiciel de compositing un minimum pro (donc pas after… *troll inside* ^^), du genre nuke, avec un format un peu pro, genre openEXR, le soft va détecter automatiquement que vous lui envoyez du linear, et va le traiter en conséquence pour vous faciliter la vie !</P>

<P>L’inconvénient de cette technique, c’est que maya lui-même subit cette règle de la gamma correction. Ce qui signifie que si vous utilisez des couleurs issues de maya et non des textures, vous aurez besoin de les dé-gamma corriger, puisqu’il ne s’agit pas d’une texture, et donc pas de quelque chose qui a été gamma-corrigé avant. Ce brave maya est décidément un peu con =) Mais on utilise en général assez peu de shaders sans textures dans le cadre d’une production, c’est ce qui m’a fait opter pour cette solution quand j’utilisais mentalRay.
Bref, pensez juste à ajouter un gammaCorrect entre votre lambert votre ramp, si jamais vous avez un shader sans texture dans votre scène.</P>

<P>Voilà le principe de la gamma correction. Après, encore une fois, les méthodes sont multiples en fonction du moteur de rendu ou du soft, donc gardez surtout à l’esprit la théorie, de sorte à comprendre ce que vous faites, quel que soit l’outil.</P>

<P>Enfin, pour terminer, sachez que maya a une tendance à rajouter des gamma corrections pour vous faciliter la vie, mais sans vous le dire… Par exemple, si vous créez un sun&sky sur mentalRay, maya va automatiquement attacher un mia_exposure photographique à votre camera, avec un attribut Gamma à 2.2, pour gamma-corriger ce que vous voyez avec cette camera. Problème, donc, si vous utilisez déjà le colorManagement (le color management a été ajouté bien après que mentalRay propose un ‘sun&sky’, ceci expliquant cela !). Remettez simplement le Gamma à 1, et le tour est joué !
</P>

<P>Voila, j’espère que tout ca vous aidera à y voir plus clair avec toutes ces histoires de gamma et de profils colorimétriques… après un petit temps de pratique et beaucoup de tests persos pour maitriser le principe sur le bout des doigts =)</P>

<BR><BR><BR><BR><BR><BR><BR>





