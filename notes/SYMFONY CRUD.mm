<map version="1.0.1">
<!-- To view this file, download free mind mapping software FreeMind from http://freemind.sourceforge.net -->
<node COLOR="#0033ff" CREATED="1694190502614" ID="ID_1507534888" MODIFIED="1694193887484" TEXT="Utiliser SYMFONY 6">
<font NAME="SansSerif" SIZE="13"/>
<node CREATED="1694192991952" ID="ID_220972721" MODIFIED="1695050648272" POSITION="left" STYLE="bubble" TEXT="CRUD">
<node CREATED="1694193023177" ID="ID_633540778" LINK="https://symfony.com/doc/current/doctrine.html#creating-an-entity-class" MODIFIED="1694193355205" TEXT="Cr&#xe9;er une Entity">
<icon BUILTIN="full-1"/>
<node CREATED="1694193597365" ID="ID_1938615284" MODIFIED="1695053310125">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      <u>symfony console make:entity</u>
    </p>
    <ol>
      <li>
        Permet d'ajouter les attributs et leurs types dans l'objet entity.
      </li>
      <li>
        C'est &#233;galement l&#224; qu'on d&#233;crit les relations inter entity.
      </li>
    </ol>
  </body>
</html>
</richcontent>
</node>
<node CREATED="1694193435607" ID="ID_1836517415" MODIFIED="1695053300051">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      <u>symfony console make:migration</u>
    </p>
    <p>
      
    </p>
    <p>
      Cette op&#233;ration fabrique un fichier SQL avec les ordres n&#233;cessaires &#224; la mise &#224; jour de la base.
    </p>
    <p>
      
    </p>
  </body>
</html>
</richcontent>
</node>
<node CREATED="1694193435607" ID="ID_1492930166" MODIFIED="1695053291621">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      <u>symfony console doctrine:migration:migrate</u>
    </p>
    <p>
      
    </p>
    <p>
      Applique le fichier SQL pr&#233;c&#233;demment construit sur la base.
    </p>
    <p>
      
    </p>
  </body>
</html>
</richcontent>
</node>
</node>
<node CREATED="1694193151853" ID="ID_909680641" MODIFIED="1694193177805" TEXT="Cr&#xe9;er une form">
<icon BUILTIN="full-2"/>
<node CREATED="1695049982036" ID="ID_826519806" MODIFIED="1695053946531">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      <u>symfony console make:form </u>
    </p>
    <p>
      
    </p>
    <ol>
      <li>
        La form est associ&#233;e &#224; une entity.
      </li>
      <li>
        Elle reprend par d&#233;faut tous ses champs.
      </li>
    </ol>
    <p>
      
    </p>
  </body>
</html>
</richcontent>
</node>
</node>
<node CREATED="1694193164713" ID="ID_259280317" MODIFIED="1694193249068" TEXT="Cr&#xe9;er un Controller">
<icon BUILTIN="full-3"/>
<node CREATED="1695050101537" ID="ID_1704651844" MODIFIED="1695050113892" TEXT="symfony console make:controller"/>
</node>
<node CREATED="1695050262175" ID="ID_1193539988" MODIFIED="1695050326603">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      Impl&#233;menter la m&#233;thode <b>Read</b>
    </p>
  </body>
</html>
</richcontent>
<icon BUILTIN="full-4"/>
</node>
<node CREATED="1695050285355" ID="ID_1436902440" MODIFIED="1695050619930">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      Impl&#233;menter la m&#233;thode <b>Create</b>
    </p>
  </body>
</html>
</richcontent>
<icon BUILTIN="full-5"/>
</node>
<node CREATED="1695050285355" ID="ID_161104266" MODIFIED="1695050622413">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      Impl&#233;menter la m&#233;thode <b>Update</b>
    </p>
  </body>
</html>
</richcontent>
<icon BUILTIN="full-6"/>
</node>
<node CREATED="1695050285355" ID="ID_404398503" MODIFIED="1695050644908">
<richcontent TYPE="NODE"><html>
  <head>
    
  </head>
  <body>
    <p>
      Impl&#233;menter la m&#233;thode <b>Delete</b>
    </p>
  </body>
</html>
</richcontent>
<icon BUILTIN="full-7"/>
</node>
</node>
</node>
</map>
