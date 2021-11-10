import {Component, OnInit} from '@angular/core';

import {Post, User} from '@app/_models';
import {ActivatedRoute, Router} from "@angular/router";
import {AuthenticationService, CommentService, PostService} from "@app/_services";
import {PostActionsAwareComponent} from "@app/shared/post-action-aware/post-actions-aware.component";
import {MatDialog} from "@angular/material/dialog";
import {CommentCreatorComponent, CommentCreatorModel} from "@app/post/comment-creator/comment-creator.component";
import {MatSnackBar} from "@angular/material/snack-bar";
import {first} from 'rxjs/operators';
import {Comment} from "@app/_models/comment";
import {MatTreeNestedDataSource} from "@angular/material/tree";
import {NestedTreeControl} from '@angular/cdk/tree';

interface CommentNode {
  author: string;
  body: string;
  id: string;
  parentId: string;
  createdAt: string[];
  children?: CommentNode[];
}

@Component({selector: 'post', templateUrl: 'post.component.html', styleUrls: ['post.component.scss']})
export class PostComponent extends PostActionsAwareComponent implements OnInit {

  postId: string;
  post: Post;
  user: User;

  treeControl = new NestedTreeControl<CommentNode>(node => node.children);
  dataSource = new MatTreeNestedDataSource<CommentNode>();


  constructor(private route: ActivatedRoute,
              private authenticationService: AuthenticationService,
              private commentService: CommentService,
              postService: PostService,
              router: Router,
              dialog: MatDialog,
              snackBar: MatSnackBar) {
    super(dialog, postService, router, snackBar);
    this.authenticationService.user.subscribe(x => this.user = x);
  }

  ngOnInit(): void {
    this.route.params.subscribe(params => {
        this.postId = params['postId'];
        this.loadPost();
      }
    )
  }

  private loadPost() {
    this.postService.getPost(this.postId)
      .subscribe(post => {
        this.post = post;
        this.dataSource.data = this.loadCommentNodes(null);
      })
  }

  private loadCommentNodes(parentId: string | null): CommentNode[] {
    const result = [];
    this.getComments(parentId).forEach(comment => {
      result.push({
        id: comment.id,
        author: comment.author,
        body: comment.body,
        parentId: comment.parentId,
        createdAt: comment.createdAt,
        children: this.loadCommentNodes(comment.id)
      })
    })
    return result;
  }

  hasChild = (_: number, node: CommentNode) => !!node.children && node.children.length > 0;

  postComment(): (postId: string, parentId: string | null) => void {
    return (postId, parentId) => {
      const dialogData = new CommentCreatorModel(postId, parentId);
      const dialogRef = this.dialog.open(CommentCreatorComponent, {
        maxWidth: "700px",
        data: dialogData
      });

      dialogRef.afterClosed().subscribe(newComment => {
        if (newComment != null) {
          this.commentService.createComment(newComment)
            .pipe(first())
            .subscribe({
              next: (response) => {
                console.log("comment created with id: " + response.id);
                this.loadPost();
                this.snackBar.open('Comment posted', '', {
                  duration: 2000
                });
              },
              error: error => {
                this.snackBar.open('Error while posting Comment: ' + error, '', {
                  duration: 2000
                });
              }
            });
        }
      });
    }

  }

  getComments(parentId: string | null): Comment[] {
    return this.post.comments
      .filter(comment => {
        return comment.parentId == parentId;
      })
  }

  afterDelete(): void {
    this.router.navigate(["/"]);
  }
}
