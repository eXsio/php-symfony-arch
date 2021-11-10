import {Component} from '@angular/core';
import {first} from 'rxjs/operators';

import {Page, PostHeader, User} from '@app/_models';
import {PageEvent} from "@angular/material/paginator";
import {AuthenticationService, PostService, UserService} from "@app/_services";
import {MatDialog} from "@angular/material/dialog";
import {PostActionsAwareComponent} from "@app/shared/post-action-aware/post-actions-aware.component";
import {ActivatedRoute, Router} from "@angular/router";
import {MatSnackBar} from "@angular/material/snack-bar";

@Component({selector: 'users', templateUrl: 'users.component.html', styleUrls: ['users.component.scss']})
export class UsersComponent extends PostActionsAwareComponent {

  posts: Page<PostHeader>;
  user: User;
  userId: string;
  pageNo: number = 1;

  constructor(private authenticationService: AuthenticationService,
              private userService: UserService,
              private route: ActivatedRoute,
              dialog: MatDialog,
              postService: PostService,
              router: Router,
              snackBar: MatSnackBar) {
    super(dialog, postService, router, snackBar);
    this.authenticationService.user.subscribe(x => this.user = x);
  }

  ngOnInit() {
    this.route.params.subscribe(params => {
      this.userId = params['userId'];
      this.loadPosts(this.pageNo);
    });
  }

  private loadPosts(pageNo: number) {
    this.userService.getPostsByUserId(this.userId, pageNo).pipe(first()).subscribe(posts => {
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
