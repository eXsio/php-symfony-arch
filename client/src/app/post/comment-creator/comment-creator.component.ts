import {Component, Inject, OnInit} from "@angular/core";
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {ConfirmDialogComponent} from "@app/shared/confirm-dialog";
import {NewComment} from "@app/_models";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
  selector: 'comment-creator',
  templateUrl: 'comment-creator.component.html',
  styleUrls: ['comment-creator.component.scss']
})
export class CommentCreatorComponent implements OnInit{

  form: FormGroup;
  private readonly postId: string;

  private readonly parentId: string | null = null;

  error = '';

  constructor(public dialogRef: MatDialogRef<ConfirmDialogComponent>,
              @Inject(MAT_DIALOG_DATA) public data: CommentCreatorModel,
              private formBuilder: FormBuilder,) {
    this.postId = data.postId;
    this.parentId = data.parentId;
  }

  ngOnInit() {
    this.form = this.formBuilder.group({
      author: ['', Validators.required],
      body: ['', Validators.required]
    });
  }


  onConfirm(): void {
    if (this.form.invalid) {
      return;
    }
    this.dialogRef.close(new NewComment(this.postId, this.f.author.value, this.f.body.value, this.parentId));
  }

  onDismiss(): void {
    this.dialogRef.close(null);
  }

  get f() {
    return this.form.controls;
  }


}


export class CommentCreatorModel {

  constructor(public postId: string, public parentId: string | null) {
  }
}
