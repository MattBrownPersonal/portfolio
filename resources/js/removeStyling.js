function removeWordStyling(paste) {
  const styleRE = new RegExp("style=", "g");
  if (!styleRE.test(paste)) return paste;
  const brPlaceholder = "@@REPLACEMENTPLACEHOLDER@@";
  let regexTarget = new RegExp("<[/]?[ ]?br>", "g");
  let replacement = brPlaceholder;
  paste = paste.replaceAll(regexTarget, replacement); // save BRs

  regexTarget = new RegExp("(\n|\r)", "g");
  replacement = " ";
  paste = paste.replaceAll(regexTarget, replacement);
  regexTarget = new RegExp("\\s?(&nbsp;)", "g");
  paste = paste.replaceAll(regexTarget, replacement);

  regexTarget = new RegExp('style=["][^"]+["]', "g");
  replacement = "";
  paste = paste.replaceAll(regexTarget, replacement);
  regexTarget = new RegExp(' class=["](MsoNormal)["]', "g");
  paste = paste.replaceAll(regexTarget, replacement);
  regexTarget = new RegExp("<o:p></o:p>", "g");
  paste = paste.replaceAll(regexTarget, replacement);
  regexTarget = new RegExp("<span\\s?></span>", "g");
  paste = paste.replaceAll(regexTarget, replacement);
  regexTarget = new RegExp("<b\\s?></b>", "g");
  paste = paste.replaceAll(regexTarget, replacement);
  regexTarget = new RegExp("<p\\s?></p>", "g");
  paste = paste.replaceAll(regexTarget, replacement);
  paste = paste.replaceAll(/<\/?[^>]+>/gi, replacement);

  replacement = '<h2 class="section-header">$2</h2>';
  regexTarget = new RegExp(
    "(<p\\s?><b\\s?><span\\s?>)(.+?)(</span></b></p>)",
    "g"
  );
  paste = paste.replaceAll(regexTarget, replacement);
  replacement = '<div class="bullet-list-item level-1"></div> ';
  regexTarget = new RegExp("●", "g");
  paste = paste.replaceAll(regexTarget, replacement);
  replacement = '<div class="bullet-list-item level-2"></div> ';
  regexTarget = new RegExp("○", "g");
  paste = paste.replaceAll(regexTarget, replacement);

  // recover BRs
  replacement = '<br>';
  regexTarget = new RegExp(brPlaceholder, "g");
  paste = paste.replaceAll(regexTarget, replacement);
  return paste;
}

export default removeWordStyling;
