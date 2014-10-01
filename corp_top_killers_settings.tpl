<!-- corp_top_killers_settings.tpl -->
{if isset($corp_top_killers_message.class)}
  <div class="corp-top-killers-msg">
    {$corp_top_killers_message.text}
  </div>
{/if}
<form method="post">
  <div><label for="corp_top_killers_limit">Corp Limit:</label></div>
  <div><input type="text" maxlength="4" size="5" value="{$corp_top_killers_limit}" name="corp_top_killers_limit" id="corp_top_killers_limit" {if isset($corp_top_killers_message.class)}class="{$corp_top_killers_message.class}"{/if}/></div>
  <div><small>How many corps to show on top list (Defaults to 10)</small></div><br />
  <div><input type="submit" value="submit" name="submit"></div>
</form><br />
<div class="block-header2"></div>
<div align="right">
  <small>Corp Top Killers Mod<br />It's fork <a href="http://www.evekb.org/forum/viewtopic.php?f=505&t=18523">Corp Top Killers Mod</a><br />$Id$</small>
</div>
<!-- /corp_top_killers_settings.tpl -->
