import {Component} from '@angular/core';
import {first} from 'rxjs/operators';

import {Page, PostHeader, Tag, User} from '@app/_models';
import {PostService} from "@app/_services/post.service";
import {PageEvent} from "@angular/material/paginator";
import {AuthenticationService, TagService} from "@app/_services";
import {MatDialog} from "@angular/material/dialog";
import {PostActionsAwareComponent} from "@app/shared/post-action-aware/post-actions-aware.component";
import {Router} from "@angular/router";
import {MatSnackBar} from "@angular/material/snack-bar";

@Component({templateUrl: 'home.component.html', styleUrls: ['home.component.scss']})
export class HomeComponent extends PostActionsAwareComponent {

  posts: Page<PostHeader>;
  user: User;
  pageNo: number = 1;
  tags: Tag[];

  constructor(private authenticationService: AuthenticationService,
              private tagService: TagService,
              postService: PostService,
              router: Router,
              dialog: MatDialog,
              snackBar: MatSnackBar) {
    super(dialog, postService, router, snackBar);
    this.authenticationService.user.subscribe(x => this.user = x);
  }


  ngOnInit() {
    this.loadPosts(this.pageNo);
    this.loadTags();
  }

  private loadTags() {
    this.tagService.getTags().pipe(first()).subscribe(tags => {
      this.tags = tags;
    });
  }

  private loadPosts(pageNo: number) {
    this.postService.getPosts(pageNo).pipe(first()).subscribe(posts => {
      this.posts = posts;
    });
  }

  public getPaginatorData(event: PageEvent): PageEvent {
    this.pageNo = event.pageIndex + 1;
    this.loadPosts(this.pageNo);
    return event;
  }

  afterDelete(): void {
    this.loadPosts(this.pageNo);
  }


}
