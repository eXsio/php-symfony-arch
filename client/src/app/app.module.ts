import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {ReactiveFormsModule} from '@angular/forms';
import {HTTP_INTERCEPTORS, HttpClientModule} from '@angular/common/http';

// used to create fake backend
import {ErrorInterceptor} from './_helpers';

import {AppComponent} from './app.component';
import {AppRoutingModule} from './app-routing.module';
import {HomeComponent} from './home';
import {LoginComponent} from './login';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations'
import {MatToolbarModule} from "@angular/material/toolbar";
import {MatIconModule} from "@angular/material/icon";
import {MatSliderModule} from "@angular/material/slider";
import {MatProgressSpinnerModule} from "@angular/material/progress-spinner";
import {MatDividerModule} from "@angular/material/divider";
import {MatButtonModule} from "@angular/material/button";
import {MatSidenavModule} from "@angular/material/sidenav";
import {MatListModule} from "@angular/material/list";
import {MatCardModule} from "@angular/material/card";
import {MatFormFieldModule} from "@angular/material/form-field";
import {MatInputModule} from "@angular/material/input";
import {MatPaginatorModule} from "@angular/material/paginator";
import {PostsListComponent} from "@app/shared/posts-list/posts-list.component";
import {MatDialogModule} from "@angular/material/dialog";
import {ConfirmDialogComponent} from "@app/shared/confirm-dialog/confirm-dialog.component";
import {PostComponent} from "@app/post/post.component";
import {TagsComponent} from "@app/tags";
import {CommentCreatorComponent} from "@app/post/comment-creator/comment-creator.component";
import {MatSnackBarModule} from "@angular/material/snack-bar";
import {CommentComponent} from "@app/post/comment/comment.component";
import {MatTreeModule} from "@angular/material/tree";
import {PostEditorComponent} from "@app/edit/post-editor.component";
import {EditorModule} from "@tinymce/tinymce-angular";
import {LatestCommentsComponent} from "@app/latest-comments/latest-comments.component";
import {MatChipsModule} from "@angular/material/chips";
import {PostTagsComponent} from "@app/shared/post-tags/post-tags.component";

;

@NgModule({
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    HttpClientModule,
    AppRoutingModule
    ,
    BrowserAnimationsModule,
    MatToolbarModule,
    MatIconModule,
    MatSliderModule,
    MatProgressSpinnerModule,
    MatDividerModule,
    MatButtonModule,
    MatSidenavModule,
    MatListModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule,
    MatPaginatorModule,
    MatDialogModule,
    MatSnackBarModule,
    MatTreeModule,
    EditorModule,
    MatChipsModule,
  ],
  declarations: [
    AppComponent,
    HomeComponent,
    LoginComponent,
    PostsListComponent,
    ConfirmDialogComponent,
    PostComponent,
    TagsComponent,
    CommentCreatorComponent,
    CommentComponent,
    PostEditorComponent,
    LatestCommentsComponent,
    PostTagsComponent
  ],
  entryComponents: [ConfirmDialogComponent],
  providers: [
    {provide: HTTP_INTERCEPTORS, useClass: ErrorInterceptor, multi: true},
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
