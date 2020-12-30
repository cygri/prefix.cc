  <div class="page-about" about="about#prefix.cc" typeof="foaf:Project">
    <h1 property="foaf:name">namespace lookup for RDF developers</h1>
    <p property="rdfs:comment" datatype="">The intention of this service is to simplify a common task in the work of <a href="http://en.wikipedia.org/wiki/Resource_Description_Framework">RDF</a> developers: remembering and looking up URI prefixes.</p>
    <p>You can look up prefixes from the search box on the homepage, or directly by typing URLs into your browser bar, such as <a href="foaf"><?php echo $base; ?>foaf</a> or <a href="foaf,dc,owl.ttl"><?php echo $base; ?>foaf,dc,owl.ttl</a>.</p>
    <p>New prefix mappings can be added by anyone. If multiple conflicting URIs are submitted for the same namespace, visitors can vote for the one they consider the best. You are only allowed one vote or namespace submission per day.</p> 
    <p rel="foaf:maker" rev="foaf:currentProject"><span about="http://richard.cyganiak.de/#me" typeof="foaf:Person">This site is maintained by <a rel="foaf:homepage" property="foaf:name" href="http://richard.cyganiak.de/">Richard Cyganiak</a>. You can contact me <a rel="foaf:mbox" href="mailto:richard@cyganiak.de">via email</a>.</span> This site was developed at the <a href="http://deri.ie/">Digital Enterprise Research Institute</a>, which is part of the <a href="http://www.nuigalway.ie/">National University of Ireland, Galway</a>. <a href="https://plus.google.com/114406186864069390644">Bernard Vatant</a>, <a href="http://ruben.verborgh.org">Ruben Verborgh</a> and <a href="https://twitter.com/MarkusLanthaler">Markus Lanthaler</a> have helped to improve the site. The code is <a href="https://github.com/cygri/prefix.cc">on Github</a>.</p>
    <p style="text-align: center">
      <a href="http://deri.ie/" class="no-caption"><img src="images/deri-logo-50px.png" alt="DERI logo" /></a>&nbsp;&nbsp;
      <a href="http://www.nuigalway.ie/" class="no-caption"><img src="images/nuig-logo-50px.png" alt="NUI Galway logo" /></a>
    </p>
    <h2>Further documentation</h2>
    <ul>
      <li><a href="about/formats">List of all supported output formats</a></li>
      <li><a href="about/api">Namespace lookup API</a></li>
      <li><a href="reverse">Reverse lookup API</a></li>
      <li><a href="about/json-ld">JSON-LD context</a></li>
      <li><a href="about/users">Tools using the API</a></li>
    </ul>
  </div>
