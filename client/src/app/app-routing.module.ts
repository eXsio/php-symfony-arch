import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

import {HomeComponent} from './home';
import {LoginComponent} from './login';
import {AuthGuard} from './_helpers';
import {PostComponent} from "@app/post/post.component";
import {UsersComponent} from "@app/users";
import {TagsComponent} from "@app/tags";
import {PostEditorComponent} from "@app/edit/post-editor.component";
import {LatestCommentsComponent} from "@app/latest-comments/latest-comments.component";

const routes: Routes = [
  {path: '', component: HomeComponent},
  {path: 'post/:postId', component: PostComponent},
  {path: 'tags/:tag', component: TagsComponent},
  {path: 'latest-comments', component: LatestCommentsComponent, canActivate: [AuthGuard]},
  {path: 'users/:userId', component: UsersComponent},
  {path: 'create', component: PostEditorComponent, canActivate: [AuthGuard]},
  {path: 'edit/:postId', component: PostEditorComponent, canActivate: [AuthGuard]},
  {path: 'login', component: LoginComponent},

  // otherwise redirect to home
  {path: '**', redirectTo: ''}
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {relativeLinkResolution: 'legacy'})],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
