{if $manage}
    <hr />


<form method="post" action="{$url_action}">	    <form method="post" action="{$url_action}">
  <input type="hidden" name="a" value="btn_manage" />	      <input type="hidden" name="a" value="btn_manage" />
  <input type="submit" value="Manage on SynergyCP" class="btn btn-info" />	      <input type="submit" value="Manage on SynergyCP" class="btn btn-info" />
</form>	    </form>
    <br />
{/if}

{if $embed}
    <iframe src="{$embedUrl}" width="100%" height="1000" name="test1" id="test1">
        <p>iFrames are not supported by your browser.</p>
    </iframe>
{/if}
