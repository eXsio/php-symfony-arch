import {MatDialog} from "@angular/material/dialog";
import {ConfirmDialogComponent, ConfirmDialogModel} from "../confirm-dialog/confirm-dialog.component";
import {PostService} from "@app/_services";
import {first} from "rxjs/operators";
import {Router} from "@angular/router";
import {MatSnackBar} from "@angular/material/snack-bar";

export abstract class PostActionsAwareComponent {

  protected constructor(protected dialog: MatDialog,
                        protected postService: PostService,
                        protected router: Router,
                        protected snackBar: MatSnackBar,) {
  }

  abstract afterDelete(): void;

  delete(): (id: string) => void {
    return postId => {
      const message = `Are you sure you want to delete this Post?`;
      const dialogData = new ConfirmDialogModel("Confirm Action", message);
      const dialogRef = this.dialog.open(ConfirmDialogComponent, {
        maxWidth: "400px",
        data: dialogData
      });

      dialogRef.afterClosed().subscribe(dialogResult => {
        if (dialogResult) {
          console.log("deleting " + postId);
          this.postService.deletePost(postId)
            .pipe(first())
            .subscribe({
              next: (response) => {
                this.afterDelete();
                this.snackBar.open('Post deleted', '', {
                  duration: 2000
                });
              },
              error: error => {
                this.snackBar.open('Error while deleting Post: ' + error, '', {
                  duration: 2000
                });
              }
            });

        }
      });
    }

  }

}
