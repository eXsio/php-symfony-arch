import {Component} from '@angular/core';
import {first} from 'rxjs/operators';

import {CommentWithPost, Page, User} from '@app/_models';
import {PageEvent} from "@angular/material/paginator";
import {AuthenticationService, CommentService} from "@app/_services";

@Component({templateUrl: 'latest-comments.component.html', styleUrls: ['latest-comments.component.scss']})
export class LatestCommentsComponent {

  loading = false;
  comments: Page<CommentWithPost>;
  user: User;
  pageNo: number = 1;

  constructor(private authenticationService: AuthenticationService,
              private commentService: CommentService) {
    this.authenticationService.user.subscribe(x => this.user = x);
  }


  ngOnInit() {
    this.loading = true;
    this.loadPosts(this.pageNo);
  }

  private loadPosts(pageNo: number) {
    this.commentService.getLatestComments(pageNo).pipe(first()).subscribe(posts => {
      this.loading = false;
      this.comments = posts;
    });
  }

  public getPaginatorData(event: PageEvent): PageEvent {
    this.pageNo = event.pageIndex + 1;
    this.loadPosts(this.pageNo);
    return event;
  }
}
