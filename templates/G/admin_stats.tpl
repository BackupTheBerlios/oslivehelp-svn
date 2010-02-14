{if $action == 'visitors'}
<div align="center">
<table cellspacing="0" cellpadding="0" width="100%" class="list">
  <tr class="main">
    <td colspan="2" align="center"><b>{$lang.visitors}</b> ({$dates})</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td width="200">{$lang.chat_requests}:</td>
    <td>{$stats.requests}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.visits}:</td>
    <td>{$stats.visits}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.hits}:</td>
    <td>{$stats.hits}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.hits_per_visit}:</td>
    <td>{$stats.hits_per_visit}</td>
  </tr>
</table>
<br /><br />
<form action="{$_SERVER.PHP_SELF}" method="post">
<table cellspacing="0" cellpadding="5" class="list">
  <tr class="main">
    <td>{$lang.from}</td>
  </tr>
  <tr class="alt">
    <td>
      {$lang.day}:
      <select name="fd">
        <option value="1"{if $fd == '1'} selected="selected"{/if}>1</option>
        <option value="2"{if $fd == '2'} selected="selected"{/if}>2</option>
        <option value="3"{if $fd == '3'} selected="selected"{/if}>3</option>
        <option value="4"{if $fd == '4'} selected="selected"{/if}>4</option>
        <option value="5"{if $fd == '5'} selected="selected"{/if}>5</option>
        <option value="6"{if $fd == '6'} selected="selected"{/if}>6</option>
        <option value="7"{if $fd == '7'} selected="selected"{/if}>7</option>
        <option value="8"{if $fd == '8'} selected="selected"{/if}>8</option>
        <option value="9"{if $fd == '9'} selected="selected"{/if}>9</option>
        <option value="10"{if $fd == '10'} selected="selected"{/if}>10</option>
        <option value="11"{if $fd == '11'} selected="selected"{/if}>11</option>
        <option value="12"{if $fd == '12'} selected="selected"{/if}>12</option>
        <option value="13"{if $fd == '13'} selected="selected"{/if}>13</option>
        <option value="14"{if $fd == '14'} selected="selected"{/if}>14</option>
        <option value="15"{if $fd == '15'} selected="selected"{/if}>15</option>
        <option value="16"{if $fd == '16'} selected="selected"{/if}>16</option>
        <option value="17"{if $fd == '17'} selected="selected"{/if}>17</option>
        <option value="18"{if $fd == '18'} selected="selected"{/if}>18</option>
        <option value="19"{if $fd == '19'} selected="selected"{/if}>19</option>
        <option value="20"{if $fd == '20'} selected="selected"{/if}>20</option>
        <option value="21"{if $fd == '21'} selected="selected"{/if}>21</option>
        <option value="22"{if $fd == '22'} selected="selected"{/if}>22</option>
        <option value="23"{if $fd == '23'} selected="selected"{/if}>23</option>
        <option value="24"{if $fd == '24'} selected="selected"{/if}>24</option>
        <option value="25"{if $fd == '25'} selected="selected"{/if}>25</option>
        <option value="26"{if $fd == '26'} selected="selected"{/if}>26</option>
        <option value="27"{if $fd == '27'} selected="selected"{/if}>27</option>
        <option value="28"{if $fd == '28'} selected="selected"{/if}>28</option>
        <option value="29"{if $fd == '29'} selected="selected"{/if}>29</option>
        <option value="30"{if $fd == '30'} selected="selected"{/if}>30</option>
        <option value="31"{if $fd == '31'} selected="selected"{/if}>31</option>
      </select>
      {$lang.month}:
      <select name="fm">
        <option value="1"{if $fm == '1'} selected="selected"{/if}>1</option>
        <option value="2"{if $fm == '2'} selected="selected"{/if}>2</option>
        <option value="3"{if $fm == '3'} selected="selected"{/if}>3</option>
        <option value="4"{if $fm == '4'} selected="selected"{/if}>4</option>
        <option value="5"{if $fm == '5'} selected="selected"{/if}>5</option>
        <option value="6"{if $fm == '6'} selected="selected"{/if}>6</option>
        <option value="7"{if $fm == '7'} selected="selected"{/if}>7</option>
        <option value="8"{if $fm == '8'} selected="selected"{/if}>8</option>
        <option value="9"{if $fm == '9'} selected="selected"{/if}>9</option>
        <option value="10"{if $fm == '10'} selected="selected"{/if}>10</option>
        <option value="11"{if $fm == '11'} selected="selected"{/if}>11</option>
        <option value="12"{if $fm == '12'} selected="selected"{/if}>12</option>
      </select>
      {$lang.year}:
      <select name="fy">
        <option value="2005"{if $fy == '2005'} selected="selected"{/if}>2005</option>
        <option value="2006"{if $fy == '2006'} selected="selected"{/if}>2006</option>
        <option value="2007"{if $fy == '2007'} selected="selected"{/if}>2007</option>
        <option value="2008"{if $fy == '2008'} selected="selected"{/if}>2008</option>
        <option value="2009"{if $fy == '2009'} selected="selected"{/if}>2009</option>
        <option value="2010"{if $fy == '2010'} selected="selected"{/if}>2010</option>
      </select>
    </td>
  </tr>
  <tr class="main">
    <td>{$lang.to}</td>
  </tr>
  <tr class="alt">
    <td>
      {$lang.day}:
      <select name="td">
        <option value="1"{if $td == '1'} selected="selected"{/if}>1</option>
        <option value="2"{if $td == '2'} selected="selected"{/if}>2</option>
        <option value="3"{if $td == '3'} selected="selected"{/if}>3</option>
        <option value="4"{if $td == '4'} selected="selected"{/if}>4</option>
        <option value="5"{if $td == '5'} selected="selected"{/if}>5</option>
        <option value="6"{if $td == '6'} selected="selected"{/if}>6</option>
        <option value="7"{if $td == '7'} selected="selected"{/if}>7</option>
        <option value="8"{if $td == '8'} selected="selected"{/if}>8</option>
        <option value="9"{if $td == '9'} selected="selected"{/if}>9</option>
        <option value="10"{if $td == '10'} selected="selected"{/if}>10</option>
        <option value="11"{if $td == '11'} selected="selected"{/if}>11</option>
        <option value="12"{if $td == '12'} selected="selected"{/if}>12</option>
        <option value="13"{if $td == '13'} selected="selected"{/if}>13</option>
        <option value="14"{if $td == '14'} selected="selected"{/if}>14</option>
        <option value="15"{if $td == '15'} selected="selected"{/if}>15</option>
        <option value="16"{if $td == '16'} selected="selected"{/if}>16</option>
        <option value="17"{if $td == '17'} selected="selected"{/if}>17</option>
        <option value="18"{if $td == '18'} selected="selected"{/if}>18</option>
        <option value="19"{if $td == '19'} selected="selected"{/if}>19</option>
        <option value="20"{if $td == '20'} selected="selected"{/if}>20</option>
        <option value="21"{if $td == '21'} selected="selected"{/if}>21</option>
        <option value="22"{if $td == '22'} selected="selected"{/if}>22</option>
        <option value="23"{if $td == '23'} selected="selected"{/if}>23</option>
        <option value="24"{if $td == '24'} selected="selected"{/if}>24</option>
        <option value="25"{if $td == '25'} selected="selected"{/if}>25</option>
        <option value="26"{if $td == '26'} selected="selected"{/if}>26</option>
        <option value="27"{if $td == '27'} selected="selected"{/if}>27</option>
        <option value="28"{if $td == '28'} selected="selected"{/if}>28</option>
        <option value="29"{if $td == '29'} selected="selected"{/if}>29</option>
        <option value="30"{if $td == '30'} selected="selected"{/if}>30</option>
        <option value="31"{if $td == '31'} selected="selected"{/if}>31</option>
      </select>
      {$lang.month}:
      <select name="tm">
        <option value="1"{if $tm == '1'} selected="selected"{/if}>1</option>
        <option value="2"{if $tm == '2'} selected="selected"{/if}>2</option>
        <option value="3"{if $tm == '3'} selected="selected"{/if}>3</option>
        <option value="4"{if $tm == '4'} selected="selected"{/if}>4</option>
        <option value="5"{if $tm == '5'} selected="selected"{/if}>5</option>
        <option value="6"{if $tm == '6'} selected="selected"{/if}>6</option>
        <option value="7"{if $tm == '7'} selected="selected"{/if}>7</option>
        <option value="8"{if $tm == '8'} selected="selected"{/if}>8</option>
        <option value="9"{if $tm == '9'} selected="selected"{/if}>9</option>
        <option value="10"{if $tm == '10'} selected="selected"{/if}>10</option>
        <option value="11"{if $tm == '11'} selected="selected"{/if}>11</option>
        <option value="12"{if $tm == '12'} selected="selected"{/if}>12</option>
      </select>
      {$lang.year}:
      <select name="ty">
        <option value="2005"{if $ty == '2005'} selected="selected"{/if}>2005</option>
        <option value="2006"{if $ty == '2006'} selected="selected"{/if}>2006</option>
        <option value="2007"{if $ty == '2007'} selected="selected"{/if}>2007</option>
        <option value="2008"{if $ty == '2008'} selected="selected"{/if}>2008</option>
        <option value="2009"{if $ty == '2009'} selected="selected"{/if}>2009</option>
        <option value="2010"{if $ty == '2010'} selected="selected"{/if}>2010</option>
      </select>
    </td>
  </tr>
</table>
<br />
<input type="submit" name="view" value="{$lang.view}">
</form>
</div>
{elseif $action == 'referrers'}
<div align="center">
<table cellspacing="0" cellpadding="0" class="list" width="100%">
  <tr class="main">
    <td colspan="2" align="center"><b>{$lang.referrers}</b></td>
  </tr>
{section name=i loop=$referrers}
  <tr class="{cycle values="alt,"}">
    <td><a href="{$referrers[i].url}" target="_blank">{$referrers[i].title}</a></td>
    <td>{$referrers[i].count}</td>
  </tr>
{/section}
</table>
<br />
<form action="{$_SERVER.PHP_SELF}?referrers" method="post">
<table cellspacing="0" cellpadding="0" class="list">
  <tr class="main">
    <td>{$lang.limit_results}: <input type="text" name="limit" value="{$limit}" size="4" /></td>
  </tr>
</table>
<br /><input type="submit" name="view" value="{$lang.view}">
</form>
</div>
{elseif $action == 'operators'}
<div align="center">
<table cellspacing="0" cellpadding="0" width="100%" class="list">
  <tr class="main">
    <td colspan="2" align="center"><b>{$operator.firstname} {$operator.lastname} ({$operator.username})</b></td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td width="200">{$lang.online_time}:</td>
    <td>{$operator.onlinetime}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.chat_accepted}:</td>
    <td>{$operator.accepts}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.chat_declined}:</td>
    <td>{$operator.declines}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.opchat_accepted}:</td>
    <td>{$operator.accepts_opchat}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.opchat_declined}:</td>
    <td>{$operator.declines_opchat}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.transfer_accepted}:</td>
    <td>{$operator.accepts_transfer}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.transfer_declined}:</td>
    <td>{$operator.declines_transfer}</td>
  </tr>
  <tr class="{cycle values="alt,"}">
    <td>{$lang.rating}:</td>
    <td>{$operator.rating} / 5</td>
  </tr>
  {if $reviews_view != 'true'}
  <tr class="{cycle values="alt,"}">
    <td>{$lang.reviews}:</td>
    <td><a href="{$_SERVER.PHP_SELF}?operators&reviews&id={$operator.id}">{$lang.reviews_view}</a></td>
  </tr>
  {/if}
</table>
{if $reviews_view == 'true'}
<br />
<form action="{$_SERVER.PHP_SELF}?operators&id={$operator.id}&reviews" method="post">
<table cellspacing="0" cellpadding="0" class="list" width="100%">
  <tr class="main">
    <td colspan="2" align="center"><b>{$lang.reviews}</b></td>
  </tr>
  {section name=i loop=$reviews}
  <tr class="alt">
    <td>{$lang.rating}: {$reviews[i].rating} / 5 {if $reviews[i].transcriptid != 0} - <a href="{$conf.url}/admin/transcripts.php?view&admin&id={$reviews[i].transcriptid}">{$lang.chat_transcript}</a>{/if}</td>
    <td align="right"><i>{$reviews[i].time}</i></td>
  </tr>
  <tr class="">
    <td colspan="2">{$reviews[i].review}</td>
  </tr>
  {/section}
  <tr class="main">
    <td colspan="2">{$lang.limit_results}: <input type="text" name="limit" size="2" value="{$limit}" /> <input type="submit" value="{$lang.view}" /></td>
  </tr>
</table>
</form>
{/if}
<br />
<form action="{$_SERVER.PHP_SELF}?operators" method="post">
<table cellspacing="0" cellpadding="0" class="list">
  <tr class="main">
    <td>{$lang.operator}:
      <select name="id">
        {section name=i loop=$operators}
        <option value="{$operators[i].id}"{if $operators[i].id == $id} selected="selected"{/if}>{$operators[i].firstname} {$operators[i].lastname} ({$operators[i].username})</option>
        {/section}
      </select>
    </td>
  </tr>
</table>
<br />
<input type="submit" name="view" value="{$lang.view}">
</form>
</div>
{/if}