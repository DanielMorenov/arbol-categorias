{extends file='page.tpl'}

{block name='titre'}My tasty bits{/block}

{block name="breadcrumb"}{/block} <!-- will remove the breadcrumb -->

{block name='page_content'}
  <h4>This is the pasta-index.tpl served by PastaController.php!</h4>
  <h5>1. Categories</h5>
  <ol>
    {foreach $categories as $category}
      <li>{$category.name}</li>
    {/foreach}
  </ol>
  
{/block}