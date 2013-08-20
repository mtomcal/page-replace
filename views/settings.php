<div ng-app="PageReplace">
  <div ng-controller="MainCtrl" ng-cloak>
  <div ng-show="message" class="{{msgclass}}">
    <p>{{message}}</p>
  </div>
  <h1>Page Replace</h1>
  <input type="text" class="regular-text" ng-model="postContents" placeholder="Keyword Search For Pages" />
  <select ng-model="type">
    <option value="post">Post</option>
    <option value="page">Page</option>
  </select>
  <button class="button-primary" ng-click="search()">Search</button>
  <img ng-show="loader" src="/wp-admin/images/wpspin_light.gif" alt="" />
<br />
<br />
<input type="text" class="text" ng-show="showReplace" ng-model="find" placeholder="Find Text"/>
<input type="text" class="text" ng-show="showReplace" ng-model="replace" placeholder="Replacing Text"/>
<img ng-show="replaceLoader" src="/wp-admin/images/wpspin_light.gif" alt="" />
<br />
<br />
<table style="width:500px;" ng-show="showTable" class="widefat">
<tr>
  <th>Title</th>
<th>Action</th>
</tr>
  <tr ng-repeat="result in results.results">
<td>
<a href="/wp-admin/post.php?post={{result.ID}}&action=edit">{{result.post_title}}</a>
</td>
<td><button ng-click="findReplace(result.ID)" class="button-secondary">Replace</button>
</td>
</tr>
</table>

  </div>
</div>
